<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsetDesa;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Exports\AsetDesaPdfExport;
use App\Exports\AsetDesaExport;
use App\Exports\AsetDesaTemplateExport;
use App\Imports\AsetDesaImport;
use Maatwebsite\Excel\Facades\Excel;

class AsetDesaController extends Controller
{
    public function index(Request $request)
    {
        $query = AsetDesa::with('desa')->current();
        
        // Filter berdasarkan desa
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori_aset')) {
            $query->where('kategori_aset', $request->kategori_aset);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        $asetDesas = $query->paginate(20)->withQueryString();
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.aset-desa.index', compact('asetDesas', 'desas'));
    }

    public function create()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.aset-desa.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'kategori_aset' => 'required|in:tanah,bangunan,inventaris',
            'nama_aset' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'nilai_sekarang' => 'nullable|numeric|min:0',
            'tanggal_perolehan' => 'required|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'required|string',
            'bukti_kepemilikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::check() ? Auth::id() : null;

        // Upload bukti kepemilikan
        if ($request->hasFile('bukti_kepemilikan')) {
            $data['bukti_kepemilikan'] = $request->file('bukti_kepemilikan')
                ->store('bukti-aset', 'public');
        }

        $aset = AsetDesa::create($data);

        // Update desa last_updated_at
        $desa = Desa::find($data['desa_id']);
        if ($desa) {
            $desa->updateLastUpdated();
        }

        return redirect()->route('admin.aset-desa.index')
            ->with('success', 'Data aset desa berhasil ditambahkan.');
    }

    public function show(AsetDesa $asetDesa)
    {
        return view('admin.aset-desa.show', compact('asetDesa'));
    }

    public function edit(AsetDesa $asetDesa)
    {
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.aset-desa.edit', compact('asetDesa', 'desas'));
    }

    public function update(Request $request, AsetDesa $asetDesa)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'kategori_aset' => 'required|in:tanah,bangunan,inventaris',
            'nama_aset' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'nilai_sekarang' => 'nullable|numeric|min:0',
            'tanggal_perolehan' => 'required|date',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'required|string',
            'bukti_kepemilikan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable|string',
            'update_reason' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::check() ? Auth::id() : null;

        // Upload bukti kepemilikan baru
        if ($request->hasFile('bukti_kepemilikan')) {
            // Hapus file lama
            if ($asetDesa->bukti_kepemilikan) {
                Storage::disk('public')->delete($asetDesa->bukti_kepemilikan);
            }
            $data['bukti_kepemilikan'] = $request->file('bukti_kepemilikan')
                ->store('bukti-aset', 'public');
        }

        $asetDesa->update($data);

        // Update desa last_updated_at
        $desa = Desa::find($data['desa_id']);
        if ($desa) {
            $desa->updateLastUpdated();
        }

        return redirect()->route('admin.aset-desa.index')
            ->with('success', 'Data aset desa berhasil diperbarui.');
    }

    public function destroy(AsetDesa $asetDesa)
    {
        $desa_id = $asetDesa->desa_id;
        
        // Hapus file bukti kepemilikan jika ada
        if ($asetDesa->bukti_kepemilikan) {
            Storage::disk('public')->delete($asetDesa->bukti_kepemilikan);
        }

        $asetDesa->delete();
        
        // Update desa last_updated_at
        $desa = Desa::find($desa_id);
        if ($desa) {
            $desa->updateLastUpdated();
        }
        
        return redirect()->route('admin.aset-desa.index')
            ->with('success', 'Data aset desa berhasil dihapus.');
    }
    
    public function riwayat(AsetDesa $aset)
    {
        $riwayat = $aset->riwayat()->with('changedBy')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.aset-desa.riwayat', compact('aset', 'riwayat'));
    }
    
    /**
     * Export data aset desa ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = AsetDesa::with('desa')->current();
        
        // Filter berdasarkan desa
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }
        
        // Filter berdasarkan kategori
        if ($request->filled('kategori_aset')) {
            $query->where('kategori_aset', $request->kategori_aset);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $asetDesas = $query->orderBy('nama_aset')->get();
        $desa = $request->desa_id ? Desa::find($request->desa_id) : null;
        
        // Jika tidak ada desa yang dipilih, ambil desa dari aset pertama jika ada
        if (!$desa && $asetDesas->count() > 0) {
            $desa = $asetDesas->first()->desa;
        }
        
        $exporter = new AsetDesaPdfExport($asetDesas, $desa);
        
        return $exporter->download();
    }

    /**
     * Export data aset desa ke Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = [
            'desa_id' => $request->desa_id,
            'kategori_aset' => $request->kategori_aset,
            'kondisi' => $request->kondisi,
            'search' => $request->search,
        ];

        $filename = 'aset-desa-' . date('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new AsetDesaExport($filters), $filename);
    }

    /**
     * Download template import aset desa
     */
    public function downloadTemplate()
    {
        $filename = 'template-aset-desa-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new AsetDesaTemplateExport(), $filename);
    }

    /**
     * Import data aset desa dari Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            Excel::import(new AsetDesaImport(), $request->file('file'));
            
            return redirect()->route('admin.aset-desa.index')
                ->with('success', 'Data aset desa berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('admin.aset-desa.index')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}