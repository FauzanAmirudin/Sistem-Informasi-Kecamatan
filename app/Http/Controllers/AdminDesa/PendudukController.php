<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendudukExport;
use App\Exports\PendudukTemplatePdfExport;
use App\Imports\PendudukImport;
use Barryvdh\DomPDF\Facade\Pdf;

class PendudukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        $query = Penduduk::with('desa')
            ->where('desa_id', $desaId);

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter berdasarkan klasifikasi usia
        if ($request->filled('klasifikasi_usia')) {
            $query->where('klasifikasi_usia', $request->klasifikasi_usia);
        }

        // Filter berdasarkan RT/RW
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        // Filter berdasarkan status KTP
        if ($request->filled('memiliki_ktp')) {
            $query->where('memiliki_ktp', $request->memiliki_ktp);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('pekerjaan', 'like', "%{$search}%");
            });
        }

        $penduduks = $query->orderBy('nama_lengkap')->paginate(20);
        $desa = Desa::find($desaId);

        // Statistik untuk filter
        $totalPenduduk = Penduduk::where('desa_id', $desaId)->count();
        $pendudukPria = Penduduk::where('desa_id', $desaId)->where('jenis_kelamin', 'L')->count();
        $pendudukWanita = Penduduk::where('desa_id', $desaId)->where('jenis_kelamin', 'P')->count();
        $pendudukBerKTP = Penduduk::where('desa_id', $desaId)->where('memiliki_ktp', true)->count();
        
        // Mendapatkan daftar RT dan RW untuk filter
        $rtList = Penduduk::where('desa_id', $desaId)->select('rt')->distinct()->orderBy('rt')->pluck('rt');
        $rwList = Penduduk::where('desa_id', $desaId)->select('rw')->distinct()->orderBy('rw')->pluck('rw');

        return view('admin-desa.penduduk.index', compact(
            'penduduks', 
            'desa', 
            'totalPenduduk', 
            'pendudukPria', 
            'pendudukWanita', 
            'pendudukBerKTP',
            'rtList',
            'rwList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        return view('admin-desa.penduduk.create', compact('desa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $desaId = Auth::user()->desa_id;
        
        $request->validate([
            'nik' => 'required|string|size:16|unique:penduduks',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|in:Tidak Sekolah,SD,SMP,SMA,D3,S1,S2,S3',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'memiliki_ktp' => 'boolean',
            'tanggal_rekam_ktp' => 'nullable|date|required_if:memiliki_ktp,1',
        ]);

        $data = $request->all();
        $data['desa_id'] = $desaId;
        $data['memiliki_ktp'] = $request->has('memiliki_ktp');

        Penduduk::create($data);
        
        // Update desa last_updated_at
        $desa = Desa::find($desaId);
        $desa->updateLastUpdated();

        return redirect()->route('admin-desa.penduduk.index')
            ->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penduduk  $penduduk
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Penduduk $penduduk)
    {
        // Pastikan penduduk milik desa yang sama dengan user
        if ($penduduk->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $penduduk->load('desa');
        return view('admin-desa.penduduk.show', compact('penduduk'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penduduk  $penduduk
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Penduduk $penduduk)
    {
        // Pastikan penduduk milik desa yang sama dengan user
        if ($penduduk->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        return view('admin-desa.penduduk.edit', compact('penduduk', 'desa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penduduk  $penduduk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Penduduk $penduduk)
    {
        // Pastikan penduduk milik desa yang sama dengan user
        if ($penduduk->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'nik' => 'required|string|size:16|unique:penduduks,nik,' . $penduduk->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|in:Tidak Sekolah,SD,SMP,SMA,D3,S1,S2,S3',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'memiliki_ktp' => 'boolean',
            'tanggal_rekam_ktp' => 'nullable|date|required_if:memiliki_ktp,1',
        ]);

        $data = $request->all();
        $data['memiliki_ktp'] = $request->has('memiliki_ktp');

        $penduduk->update($data);
        $penduduk->refresh();

        // Update desa last_updated_at
        $penduduk->desa->updateLastUpdated();

        return redirect()->route('admin-desa.penduduk.index')
            ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penduduk  $penduduk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Penduduk $penduduk)
    {
        // Pastikan penduduk milik desa yang sama dengan user
        if ($penduduk->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $desa = $penduduk->desa;
        $penduduk->delete();
        
        // Update desa last_updated_at
        $desa->updateLastUpdated();

        return redirect()->route('admin-desa.penduduk.index')
            ->with('success', 'Data penduduk berhasil dihapus.');
    }

    /**
     * Export data penduduk ke Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $desaId = Auth::user()->desa_id;
        
        $filters = [
            'desa_id' => $desaId,
            'jenis_kelamin' => $request->jenis_kelamin,
            'klasifikasi_usia' => $request->klasifikasi_usia,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'search' => $request->search,
        ];

        $filename = 'data-penduduk-' . date('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new PendudukExport($filters), $filename);
    }

    /**
     * Export data penduduk ke PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $desaId = Auth::user()->desa_id;
        
        $query = Penduduk::with('desa')
            ->where('desa_id', $desaId);

        // Apply same filters as index
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('klasifikasi_usia')) {
            $query->where('klasifikasi_usia', $request->klasifikasi_usia);
        }
        
        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }
        
        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $penduduks = $query->orderBy('nama_lengkap')->get();
        $desa = Desa::find($desaId);

        $pdf = Pdf::loadView('admin-desa.penduduk.pdf', compact('penduduks', 'desa'));
        
        $filename = 'laporan-penduduk-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Import data penduduk dari Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $desaId = Auth::user()->desa_id;
        
        try {
            Excel::import(new PendudukImport($desaId), $request->file('file'));
            
            // Update desa last_updated_at
            $desa = Desa::find($desaId);
            $desa->updateLastUpdated();
            
            return redirect()->route('admin-desa.penduduk.index')
                ->with('success', 'Data penduduk berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('admin-desa.penduduk.index')
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
    
    /**
     * Download template Excel untuk import data penduduk.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate(Request $request)
    {
        \Log::info('AdminDesa downloadTemplate hit', [
            'url' => $request->fullUrl(),
            'user_id' => optional(auth()->user())->id,
        ]);

        $validated = $request->validate([
            'jumlah_baris' => 'nullable|integer|min:1|max:1000',
            'kolom' => 'nullable|array',
            'kolom.*' => 'string',
        ]);

        $numRows = (int)($validated['jumlah_baris'] ?? 50);
        $columns = $validated['kolom'] ?? null;

        $filename = 'template-import-penduduk.xlsx';
        return Excel::download(new \App\Exports\PendudukTemplateExport($columns, $numRows), $filename);
    }

    /**
     * Download template PDF untuk data penduduk
     */
    public function downloadTemplatePdf(Request $request)
    {
        try {
            $request->validate([
                'jumlah_baris' => 'required|integer|min:10|max:200',
            ]);

            $jumlahBaris = $request->jumlah_baris;
            
            $exporter = new PendudukTemplatePdfExport($jumlahBaris);
            return $exporter->download();
        } catch (\Exception $e) {
            return redirect()->route('admin-desa.penduduk.index')
                ->with('error', 'Gagal download template PDF: ' . $e->getMessage());
        }
    }
}