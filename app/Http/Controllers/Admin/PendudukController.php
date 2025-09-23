<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendudukExport;
use App\Exports\PendudukTemplateExport;
use App\Exports\PendudukTemplatePdfExport;
use App\Imports\PendudukImport;
use Barryvdh\DomPDF\Facade\Pdf;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $query = Penduduk::with('desa');

        // Filter berdasarkan desa
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

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
        $desas = Desa::orderBy('nama_desa')->get();

        // Statistik untuk filter
        $totalPenduduk = Penduduk::count();
        $pendudukPria = Penduduk::where('jenis_kelamin', 'L')->count();
        $pendudukWanita = Penduduk::where('jenis_kelamin', 'P')->count();
        $pendudukBerKTP = Penduduk::where('memiliki_ktp', true)->count();

        return view('admin.penduduk.index', compact(
            'penduduks', 
            'desas', 
            'totalPenduduk', 
            'pendudukPria', 
            'pendudukWanita', 
            'pendudukBerKTP'
        ));
    }

    public function create()
    {
        $desas = Desa::where('status', 'aktif')->orderBy('nama_desa')->get();
        return view('admin.penduduk.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
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
        $data['memiliki_ktp'] = $request->has('memiliki_ktp');

        Penduduk::create($data);

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function show(Penduduk $penduduk)
    {
        $penduduk->load('desa');
        return view('admin.penduduk.show', compact('penduduk'));
    }

    public function edit(Penduduk $penduduk)
    {
        $desas = Desa::where('status', 'aktif')->orderBy('nama_desa')->get();
        return view('admin.penduduk.edit', compact('penduduk', 'desas'));
    }

    public function update(Request $request, Penduduk $penduduk)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
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

        // Update desa last_updated_at
        $penduduk->desa->updateLastUpdated();

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy(Penduduk $penduduk)
    {
        $desa = $penduduk->desa;
        $penduduk->delete();
        
        // Update desa last_updated_at
        $desa->updateLastUpdated();

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $filters = [
            'desa_id' => $request->desa_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'klasifikasi_usia' => $request->klasifikasi_usia,
            'search' => $request->search,
        ];

        $filename = 'data-penduduk-' . date('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new PendudukExport($filters), $filename);
    }

    public function exportPdf(Request $request)
    {
        $query = Penduduk::with('desa');

        // Apply same filters as index
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
            $desa = Desa::find($request->desa_id);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('klasifikasi_usia')) {
            $query->where('klasifikasi_usia', $request->klasifikasi_usia);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $penduduks = $query->orderBy('nama_lengkap')->get();

        $pdf = Pdf::loadView('admin.penduduk.pdf', compact('penduduks'));
        
        $filename = 'laporan-penduduk-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new PendudukImport, $request->file('file'));

            return redirect()->route('admin.penduduk.index')
                ->with('success', 'Data penduduk berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('admin.penduduk.index')
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
    
    /**
     * Download template Excel untuk import data penduduk
     */
    public function downloadTemplate(Request $request)
    {
        $validated = $request->validate([
            'jumlah_baris' => 'nullable|integer|min:1|max:1000',
            'kolom' => 'nullable|array',
            'kolom.*' => 'string',
        ]);

        $numRows = (int)($validated['jumlah_baris'] ?? 50);
        $columns = $validated['kolom'] ?? null;

        $filename = 'template-import-penduduk-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new PendudukTemplateExport($columns, $numRows), $filename);
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
            return redirect()->route('admin.penduduk.index')
                ->with('error', 'Gagal download template PDF: ' . $e->getMessage());
        }
    }
    
}