<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\AsetTanahWarga;
use App\Models\Desa;
use App\Exports\AsetTanahWargaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AsetTanahWargaController extends Controller
{
    /**
     * Download bukti kepemilikan aset tanah warga
     *
     * @param AsetTanahWarga $asetTanahWarga
     * @return \Illuminate\Http\RedirectResponse|BinaryFileResponse
     */
    public function downloadBuktiKepemilikan(AsetTanahWarga $asetTanahWarga)
    {
        // Pastikan aset tanah warga milik desa yang sama dengan user
        if ($asetTanahWarga->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan file bukti kepemilikan ada
        if (!$asetTanahWarga->bukti_kepemilikan) {
            return redirect()->back()->with('error', 'File bukti kepemilikan tidak ditemukan.');
        }

        // Pastikan file ada di storage
        if (!Storage::disk('uploads')->exists($asetTanahWarga->bukti_kepemilikan)) {
            return redirect()->back()->with('error', 'File bukti kepemilikan tidak ditemukan di server.');
        }

        // Dapatkan ekstensi file asli
        $extension = pathinfo($asetTanahWarga->bukti_kepemilikan, PATHINFO_EXTENSION);
        
        // Buat nama file yang lebih deskriptif
        $filename = 'Bukti_Kepemilikan_' . str_replace(' ', '_', $asetTanahWarga->nama_pemilik) . '_' . $asetTanahWarga->nomor_sph . '.' . $extension;
        
        // Download file
        $absolutePath = Storage::disk('uploads')->path($asetTanahWarga->bukti_kepemilikan);
        return response()->download($absolutePath, $filename);
    }
    
    public function index(Request $request)
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        $query = AsetTanahWarga::with('desa')
            ->where('desa_id', $desaId);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pemilik', 'like', "%{$search}%")
                  ->orWhere('nik_pemilik', 'like', "%{$search}%")
                  ->orWhere('nomor_sph', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan jenis tanah
        if ($request->filled('jenis_tanah')) {
            $query->where('jenis_tanah', $request->jenis_tanah);
        }
        
        // Filter berdasarkan status kepemilikan
        if ($request->filled('status_kepemilikan')) {
            $query->where('status_kepemilikan', $request->status_kepemilikan);
        }
        
        $asetTanahWargas = $query->latest()->paginate(20)->withQueryString();
        $desa = Desa::find($desaId);
        
        // Statistik
        $totalAsetTanah = AsetTanahWarga::where('desa_id', $desaId)->count();
        $tanahKering = AsetTanahWarga::where('desa_id', $desaId)->where('jenis_tanah', 'tanah_kering')->count();
        $tanahSawah = AsetTanahWarga::where('desa_id', $desaId)->where('jenis_tanah', 'tanah_sawah')->count();
        $tanahPekarangan = AsetTanahWarga::where('desa_id', $desaId)->where('jenis_tanah', 'tanah_pekarangan')->count();
        $tanahPerkebunan = AsetTanahWarga::where('desa_id', $desaId)->where('jenis_tanah', 'tanah_perkebunan')->count();
        
        // Data untuk grafik
        $dataGrafik = [
            'labels' => ['Tanah Kering', 'Tanah Sawah', 'Tanah Pekarangan', 'Tanah Perkebunan'],
            'data' => [$tanahKering, $tanahSawah, $tanahPekarangan, $tanahPerkebunan],
        ];
        
        return view('admin-desa.aset-tanah-warga.index', compact(
            'asetTanahWargas', 
            'desa', 
            'totalAsetTanah', 
            'tanahKering', 
            'tanahSawah', 
            'tanahPekarangan', 
            'tanahPerkebunan',
            'dataGrafik'
        ));
    }

    public function create()
    {
        $desa = Desa::find(Auth::user()->desa_id);
        return view('admin-desa.aset-tanah-warga.create', compact('desa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|size:16',
            'nomor_sph' => 'required|string|max:100|unique:aset_tanah_wargas,nomor_sph',
            'tanggal_sph' => 'required|date',
            'jenis_tanah' => 'required|string|in:tanah_kering,tanah_sawah,tanah_pekarangan,tanah_perkebunan',
            'status_kepemilikan' => 'required|string|in:milik_sendiri,warisan,hibah,jual_beli',
            'luas_tanah' => 'required|numeric|min:0',
            'nilai_per_meter' => 'required|numeric|min:0',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
            'tanggal_perolehan' => 'required|date',
            'bukti_kepemilikan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Set desa_id dari user yang login
        $validated['desa_id'] = Auth::user()->desa_id;
        
        // Upload bukti kepemilikan
        if ($request->hasFile('bukti_kepemilikan')) {
            $buktiPath = $request->file('bukti_kepemilikan')->store('aset-tanah-warga', 'uploads');
            $validated['bukti_kepemilikan'] = $buktiPath;
        }
        
        // Buat data aset tanah warga
        $asetTanahWarga = AsetTanahWarga::create($validated);
        
        // Update last_updated_at pada desa
        $desa = Desa::find(Auth::user()->desa_id);
        $desa->update(['last_updated_at' => now()]);
        
        return redirect()->route('admin-desa.aset-tanah-warga.index')
            ->with('success', 'Data aset tanah warga berhasil ditambahkan.');
    }

    public function show(AsetTanahWarga $asetTanahWarga)
    {
        // Pastikan aset tanah warga milik desa yang sama dengan user
        if ($asetTanahWarga->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('admin-desa.aset-tanah-warga.show', compact('asetTanahWarga'));
    }

    public function edit(AsetTanahWarga $asetTanahWarga)
    {
        // Pastikan aset tanah warga milik desa yang sama dengan user
        if ($asetTanahWarga->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $desa = Desa::find(Auth::user()->desa_id);
        return view('admin-desa.aset-tanah-warga.edit', compact('asetTanahWarga', 'desa'));
    }

    public function update(Request $request, AsetTanahWarga $asetTanahWarga)
    {
        // Pastikan aset tanah warga milik desa yang sama dengan user
        if ($asetTanahWarga->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|size:16',
            'nomor_sph' => 'required|string|max:100|unique:aset_tanah_wargas,nomor_sph,' . $asetTanahWarga->id,
            'jenis_tanah' => 'required|string|in:tanah_kering,tanah_sawah,tanah_pekarangan,tanah_perkebunan',
            'status_kepemilikan' => 'required|string|in:milik_sendiri,warisan,hibah,jual_beli',
            'luas_tanah' => 'required|numeric|min:0',
            'nilai_per_meter' => 'required|numeric|min:0',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
            'tanggal_perolehan' => 'required|date',
            'bukti_kepemilikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Upload bukti kepemilikan baru jika ada
        if ($request->hasFile('bukti_kepemilikan')) {
            // Hapus file lama jika ada
            if ($asetTanahWarga->bukti_kepemilikan) {
                Storage::disk('uploads')->delete($asetTanahWarga->bukti_kepemilikan);
            }
            
            $buktiPath = $request->file('bukti_kepemilikan')->store('aset-tanah-warga', 'uploads');
            $validated['bukti_kepemilikan'] = $buktiPath;
        }
        
        // Update data aset tanah warga
        $asetTanahWarga->update($validated);
        
        // Update last_updated_at pada desa
        $desa = Desa::find(Auth::user()->desa_id);
        $desa->update(['last_updated_at' => now()]);
        
        return redirect()->route('admin-desa.aset-tanah-warga.index')
            ->with('success', 'Data aset tanah warga berhasil diperbarui.');
    }

    public function destroy(AsetTanahWarga $asetTanahWarga)
    {
        // Pastikan aset tanah warga milik desa yang sama dengan user
        if ($asetTanahWarga->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Hapus file bukti kepemilikan jika ada
            if ($asetTanahWarga->bukti_kepemilikan) {
                Storage::disk('uploads')->delete($asetTanahWarga->bukti_kepemilikan);
            }
            
            // Hapus data
            $asetTanahWarga->delete();
            
            // Update last_updated_at pada desa
            $desa = Desa::find(Auth::user()->desa_id);
            $desa->update(['last_updated_at' => now()]);
            
            return redirect()->route('admin-desa.aset-tanah-warga.index')
                ->with('success', 'Data aset tanah warga berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Export data aset tanah warga ke Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $desaId = Auth::user()->desa_id;
        $jenisTanah = $request->jenis_tanah;
        $search = $request->search;
        
        $fileName = 'data_aset_tanah_warga_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new AsetTanahWargaExport($desaId, $jenisTanah, $search), $fileName);
    }
    
    /**
     * Menampilkan halaman rekap aset tanah warga
     *
     * @return \Illuminate\View\View
     */
    public function rekap()
    {
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        
        // Statistik umum
        $totalSPH = AsetTanahWarga::where('desa_id', $desaId)->count();
        $totalLuas = AsetTanahWarga::where('desa_id', $desaId)->sum('luas_tanah');
        $totalNilai = AsetTanahWarga::where('desa_id', $desaId)
            ->selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')
            ->first()
            ->total_nilai ?? 0;
        $rataRataNilai = $totalLuas > 0 ? $totalNilai / $totalLuas : 0;
        
        // Data untuk grafik jenis tanah
        $jenisTanah = AsetTanahWarga::where('desa_id', $desaId)
            ->select('jenis_tanah', DB::raw('count(*) as total'), DB::raw('SUM(luas_tanah) as total_luas'), DB::raw('SUM(luas_tanah * nilai_per_meter) as total_nilai'))
            ->groupBy('jenis_tanah')
            ->get();
            
        // Data untuk grafik status kepemilikan
        $statusKepemilikan = AsetTanahWarga::where('desa_id', $desaId)
            ->select('status_kepemilikan', DB::raw('count(*) as total'))
            ->groupBy('status_kepemilikan')
            ->get();
            
        // Format data untuk grafik
        $labelJenis = [];
        $dataJumlahJenis = [];
        $dataLuasJenis = [];
        $dataNilaiJenis = [];
        $warnaBgJenis = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
        
        $labelStatus = [];
        $dataJumlahStatus = [];
        $warnaBgStatus = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
        
        $i = 0;
        foreach ($jenisTanah as $jenis) {
            $namaJenis = str_replace('_', ' ', ucwords($jenis->jenis_tanah));
            $labelJenis[] = $namaJenis;
            $dataJumlahJenis[] = $jenis->total;
            $dataLuasJenis[] = $jenis->total_luas;
            $dataNilaiJenis[] = $jenis->total_nilai;
            $i++;
        }
        
        $i = 0;
        foreach ($statusKepemilikan as $status) {
            $namaStatus = str_replace('_', ' ', ucwords($status->status_kepemilikan));
            $labelStatus[] = $namaStatus;
            $dataJumlahStatus[] = $status->total;
            $i++;
        }
        
        // Data untuk tabel rekap
        $rekapData = [];
        $totalJumlah = 0;
        $totalLuasAll = 0;
        $totalNilaiAll = 0;
        
        foreach ($jenisTanah as $jenis) {
            $namaJenis = str_replace('_', ' ', ucwords($jenis->jenis_tanah));
            $persenJumlah = $totalSPH > 0 ? ($jenis->total / $totalSPH) * 100 : 0;
            $persenLuas = $totalLuas > 0 ? ($jenis->total_luas / $totalLuas) * 100 : 0;
            $persenNilai = $totalNilai > 0 ? ($jenis->total_nilai / $totalNilai) * 100 : 0;
            
            $rekapData[] = [
                'jenis' => $namaJenis,
                'jumlah' => $jenis->total,
                'persen_jumlah' => $persenJumlah,
                'luas' => $jenis->total_luas,
                'persen_luas' => $persenLuas,
                'nilai' => $jenis->total_nilai,
                'persen_nilai' => $persenNilai,
            ];
            
            $totalJumlah += $jenis->total;
            $totalLuasAll += $jenis->total_luas;
            $totalNilaiAll += $jenis->total_nilai;
        }
        
        return view('admin-desa.aset-tanah-warga.rekap', compact(
            'desa',
            'totalSPH',
            'totalLuas',
            'totalNilai',
            'rataRataNilai',
            'labelJenis',
            'dataJumlahJenis',
            'dataLuasJenis',
            'dataNilaiJenis',
            'warnaBgJenis',
            'labelStatus',
            'dataJumlahStatus',
            'warnaBgStatus',
            'rekapData',
            'totalJumlah',
            'totalLuasAll',
            'totalNilaiAll'
        ));
    }
    
    /**
     * Export rekap data aset tanah warga ke Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportRekapExcel()
    {
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        
        // Statistik umum
        $totalSPH = AsetTanahWarga::where('desa_id', $desaId)->count();
        $totalLuas = AsetTanahWarga::where('desa_id', $desaId)->sum('luas_tanah');
        $totalNilai = AsetTanahWarga::where('desa_id', $desaId)
            ->selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')
            ->first()
            ->total_nilai ?? 0;
        
        // Data untuk tabel rekap
        $jenisTanah = AsetTanahWarga::where('desa_id', $desaId)
            ->select('jenis_tanah', DB::raw('count(*) as total'), DB::raw('SUM(luas_tanah) as total_luas'), DB::raw('SUM(luas_tanah * nilai_per_meter) as total_nilai'))
            ->groupBy('jenis_tanah')
            ->get();
        
        // Buat spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set judul
        $sheet->setCellValue('A1', 'REKAP ASET TANAH WARGA');
        $sheet->setCellValue('A2', 'DESA ' . strtoupper($desa->nama_desa));
        $sheet->setCellValue('A3', 'TANGGAL: ' . date('d-m-Y'));
        
        // Merge cells untuk judul
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        
        // Style untuk judul
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Header tabel
        $sheet->setCellValue('A5', 'NO');
        $sheet->setCellValue('B5', 'JENIS TANAH');
        $sheet->setCellValue('C5', 'JUMLAH');
        $sheet->setCellValue('D5', 'PERSENTASE');
        $sheet->setCellValue('E5', 'LUAS (m²)');
        $sheet->setCellValue('F5', 'NILAI TOTAL (Rp)');
        
        // Style untuk header tabel
        $sheet->getStyle('A5:F5')->getFont()->setBold(true);
        $sheet->getStyle('A5:F5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:F5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');
        
        // Isi tabel
        $row = 6;
        $no = 1;
        foreach ($jenisTanah as $jenis) {
            $namaJenis = str_replace('_', ' ', ucwords($jenis->jenis_tanah));
            $persenJumlah = $totalSPH > 0 ? ($jenis->total / $totalSPH) * 100 : 0;
            
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $namaJenis);
            $sheet->setCellValue('C' . $row, $jenis->total);
            $sheet->setCellValue('D' . $row, number_format($persenJumlah, 2) . '%');
            $sheet->setCellValue('E' . $row, $jenis->total_luas);
            $sheet->setCellValue('F' . $row, $jenis->total_nilai);
            
            $row++;
            $no++;
        }
        
        // Total
        $sheet->setCellValue('A' . $row, '');
        $sheet->setCellValue('B' . $row, 'TOTAL');
        $sheet->setCellValue('C' . $row, $totalSPH);
        $sheet->setCellValue('D' . $row, '100%');
        $sheet->setCellValue('E' . $row, $totalLuas);
        $sheet->setCellValue('F' . $row, $totalNilai);
        
        // Style untuk total
        $sheet->getStyle('B' . $row . ':F' . $row)->getFont()->setBold(true);
        
        // Format angka
        $sheet->getStyle('F6:F' . $row)->getNumberFormat()->setFormatCode('_-* #,##0_-;-* #,##0_-;_-* "-"_-;_-@_-');
        $sheet->getStyle('E6:E' . $row)->getNumberFormat()->setFormatCode('_-* #,##0.00_-;-* #,##0.00_-;_-* "-"??_-;_-@_-');
        
        // Auto size kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Buat file Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'rekap_aset_tanah_warga_' . $desa->nama_desa . '_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'rekap_');
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    
    /**
     * Export rekap data aset tanah warga ke PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function exportRekapPdf()
    {
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        
        // Statistik umum
        $totalSPH = AsetTanahWarga::where('desa_id', $desaId)->count();
        $totalLuas = AsetTanahWarga::where('desa_id', $desaId)->sum('luas_tanah');
        $totalNilai = AsetTanahWarga::where('desa_id', $desaId)
            ->selectRaw('SUM(luas_tanah * nilai_per_meter) as total_nilai')
            ->first()
            ->total_nilai ?? 0;
        $rataRataNilai = $totalLuas > 0 ? $totalNilai / $totalLuas : 0;
        
        // Data untuk tabel rekap
        $jenisTanah = AsetTanahWarga::where('desa_id', $desaId)
            ->select('jenis_tanah', DB::raw('count(*) as total'), DB::raw('SUM(luas_tanah) as total_luas'), DB::raw('SUM(luas_tanah * nilai_per_meter) as total_nilai'))
            ->groupBy('jenis_tanah')
            ->get();
            
        $rekapData = [];
        foreach ($jenisTanah as $jenis) {
            $namaJenis = str_replace('_', ' ', ucwords($jenis->jenis_tanah));
            $persenJumlah = $totalSPH > 0 ? ($jenis->total / $totalSPH) * 100 : 0;
            $persenLuas = $totalLuas > 0 ? ($jenis->total_luas / $totalLuas) * 100 : 0;
            $persenNilai = $totalNilai > 0 ? ($jenis->total_nilai / $totalNilai) * 100 : 0;
            
            $rekapData[] = [
                'jenis' => $namaJenis,
                'jumlah' => $jenis->total,
                'persen_jumlah' => $persenJumlah,
                'luas' => $jenis->total_luas,
                'persen_luas' => $persenLuas,
                'nilai' => $jenis->total_nilai,
                'persen_nilai' => $persenNilai,
            ];
        }
        
        $data = [
            'desa' => $desa,
            'totalSPH' => $totalSPH,
            'totalLuas' => $totalLuas,
            'totalNilai' => $totalNilai,
            'rataRataNilai' => $rataRataNilai,
            'rekapData' => $rekapData,
            'tanggal' => date('d-m-Y'),
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin-desa.aset-tanah-warga.pdf.rekap', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('rekap_aset_tanah_warga_' . $desa->nama_desa . '_' . date('Y-m-d') . '.pdf');
    }
}