<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsetTanahWarga;
use App\Models\Desa;
use App\Exports\AsetTanahWargaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $query = AsetTanahWarga::with('desa');
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pemilik', 'like', "%{$search}%")
                  ->orWhere('nik_pemilik', 'like', "%{$search}%")
                  ->orWhere('nomor_sph', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan desa
        if ($request->has('desa_id') && $request->desa_id) {
            $query->where('desa_id', $request->desa_id);
        }
        
        // Filter berdasarkan status kepemilikan
        if ($request->has('status_kepemilikan') && $request->status_kepemilikan) {
            $query->where('status_kepemilikan', $request->status_kepemilikan);
        }
        
        $asetTanahWargas = $query->latest()->paginate(20)->withQueryString();
        $desas = Desa::orderBy('nama_desa')->get();
        
        return view('admin.aset-tanah-warga.index', compact('asetTanahWargas', 'desas'));
    }

    public function create()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.aset-tanah-warga.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|size:16',
            'nomor_sph' => 'required|string|max:100',
            'jenis_tanah' => 'required|string|in:tanah_kering,tanah_sawah,tanah_pekarangan,tanah_perkebunan',
            'status_kepemilikan' => 'required|string|in:milik_sendiri,warisan,hibah,jual_beli',
            'luas_tanah' => 'required|numeric|min:0',
            'nilai_per_meter' => 'required|numeric|min:0',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
            'tanggal_perolehan' => 'required|date',
            'bukti_kepemilikan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Upload bukti kepemilikan
        if ($request->hasFile('bukti_kepemilikan')) {
            $buktiPath = $request->file('bukti_kepemilikan')->store('aset-tanah-warga', 'uploads');
            $validated['bukti_kepemilikan'] = $buktiPath;
        }
        
        // Buat data aset tanah warga
        $asetTanahWarga = AsetTanahWarga::create($validated);
        
        // Update last_updated_at pada desa
        $desa = Desa::find($request->desa_id);
        $desa->update(['last_updated_at' => now()]);
        
        return redirect()->route('admin.aset-tanah-warga.index')
            ->with('success', 'Data aset tanah warga berhasil ditambahkan.');
    }

    public function show(AsetTanahWarga $asetTanahWarga)
    {
        return view('admin.aset-tanah-warga.show', compact('asetTanahWarga'));
    }

    public function edit(AsetTanahWarga $asetTanahWarga)
    {
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.aset-tanah-warga.edit', compact('asetTanahWarga', 'desas'));
    }

    public function update(Request $request, AsetTanahWarga $asetTanahWarga)
    {
        $validated = $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|size:16',
            'nomor_sph' => 'required|string|max:100',
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
        $desa = Desa::find($request->desa_id);
        $desa->update(['last_updated_at' => now()]);
        
        return redirect()->route('admin.aset-tanah-warga.index')
            ->with('success', 'Data aset tanah warga berhasil diperbarui.');
    }

    public function destroy(AsetTanahWarga $asetTanahWarga)
    {
        // Hapus file bukti kepemilikan jika ada
        if ($asetTanahWarga->bukti_kepemilikan) {
            Storage::disk('uploads')->delete($asetTanahWarga->bukti_kepemilikan);
        }
        
        // Hapus data aset tanah warga
        $asetTanahWarga->delete();
        
        // Update last_updated_at pada desa
        $desa = Desa::find($asetTanahWarga->desa_id);
        $desa->update(['last_updated_at' => now()]);
        
        return redirect()->route('admin.aset-tanah-warga.index')
            ->with('success', 'Data aset tanah warga berhasil dihapus.');
    }
    
    /**
     * Export data aset tanah warga ke Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $desaId = $request->desa_id;
        $jenisTanah = $request->jenis_tanah;
        $search = $request->search;
        
        $fileName = 'data_aset_tanah_warga_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new AsetTanahWargaExport($desaId, $jenisTanah, $search), $fileName);
    }
}