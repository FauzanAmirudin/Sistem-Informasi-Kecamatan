<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\AsetDesa;
use App\Models\Desa;
use App\Models\RiwayatAsetDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exports\AsetDesaPdfExport;
use App\Exports\AsetDesaExport;
use App\Exports\AsetDesaTemplateExport;
use App\Imports\AsetDesaImport;
use Maatwebsite\Excel\Facades\Excel;

class AsetDesaController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        $query = AsetDesa::with('desa')
            ->where('desa_id', $desaId)
            ->current();

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

        $asetDesas = $query->orderBy('nama_aset')->paginate(20);
        $desa = Desa::find($desaId);

        // Statistik
        $totalAset = AsetDesa::where('desa_id', $desaId)->current()->count();
        $asetBaik = AsetDesa::where('desa_id', $desaId)->current()->where('kondisi', 'baik')->count();
        $asetRusakRingan = AsetDesa::where('desa_id', $desaId)->current()->where('kondisi', 'rusak_ringan')->count();
        $asetRusakBerat = AsetDesa::where('desa_id', $desaId)->current()->where('kondisi', 'rusak_berat')->count();

        return view('admin-desa.aset-desa.index', compact(
            'asetDesas', 
            'desa', 
            'totalAset', 
            'asetBaik', 
            'asetRusakRingan', 
            'asetRusakBerat'
        ));
    }

    public function create()
    {
        $desa = Desa::find(Auth::user()->desa_id);
        return view('admin-desa.aset-desa.create', compact('desa'));
    }

    public function store(Request $request)
    {
        $request->validate([
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
        $data['desa_id'] = Auth::user()->desa_id;
        $data['updated_by'] = Auth::id();

        // Upload bukti kepemilikan
        if ($request->hasFile('bukti_kepemilikan')) {
            $data['bukti_kepemilikan'] = $request->file('bukti_kepemilikan')
                ->store('bukti-aset', 'public');
        }

        $aset = AsetDesa::create($data);

        // Update desa last_updated_at
        $aset->desa->updateLastUpdated();

        return redirect()->route('admin-desa.aset-desa.index')
            ->with('success', 'Data aset desa berhasil ditambahkan.');
    }

    public function show(AsetDesa $asetDesa)
    {
        // Pastikan aset desa milik desa yang sama dengan user
        if ($asetDesa->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $asetDesa->load('desa', 'updatedBy');
        return view('admin-desa.aset-desa.show', compact('asetDesa'));
    }

    public function edit(AsetDesa $asetDesa)
    {
        // Pastikan aset desa milik desa yang sama dengan user
        if ($asetDesa->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $desa = Desa::find(Auth::user()->desa_id);
        return view('admin-desa.aset-desa.edit', compact('asetDesa', 'desa'));
    }

    public function update(Request $request, AsetDesa $asetDesa)
    {
        try {
            // Debug untuk melihat request yang masuk
            Log::info('Update Aset Desa - Request:', [
                'request_all' => $request->all(),
                'files' => $request->allFiles(),
                'aset_desa_id' => $asetDesa->id,
                'user_desa_id' => Auth::user()->desa_id
            ]);

            // Pastikan aset desa milik desa yang sama dengan user
            if ($asetDesa->desa_id != Auth::user()->desa_id) {
                Log::error('Update Aset Desa - Unauthorized:', [
                    'aset_desa_id' => $asetDesa->id,
                    'aset_desa_desa_id' => $asetDesa->desa_id,
                    'user_desa_id' => Auth::user()->desa_id
                ]);
                abort(403, 'Unauthorized action.');
            }
            
            // Validasi request
            $validated = $request->validate([
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

            // Simpan data lama untuk riwayat
            $oldData = $asetDesa->toArray();

            // Set data baru
            $data = $request->all();
            $data['updated_by'] = Auth::id();

            // Upload bukti kepemilikan baru jika ada
            if ($request->hasFile('bukti_kepemilikan')) {
                // Hapus file lama jika ada
                if ($asetDesa->bukti_kepemilikan) {
                    Storage::disk('public')->delete($asetDesa->bukti_kepemilikan);
                }
                
                $data['bukti_kepemilikan'] = $request->file('bukti_kepemilikan')
                    ->store('bukti-aset', 'public');
            }

            // Nonaktifkan data lama
            $asetDesa->update(['is_current' => false]);

            // Buat data baru dengan data yang diupdate
            $newAset = AsetDesa::create(array_merge($oldData, $data, ['is_current' => true]));

            // Buat riwayat
            $newAset->createHistory('update', Auth::id(), $request->update_reason);

            // Update desa last_updated_at
            $newAset->desa->updateLastUpdated();

            return redirect()->route('admin-desa.aset-desa.show', $newAset)
                ->with('success', 'Data aset desa berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating aset desa: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(AsetDesa $asetDesa)
    {
        // Pastikan aset desa milik desa yang sama dengan user
        if ($asetDesa->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Nonaktifkan data
            $asetDesa->update(['is_current' => false]);
            
            // Buat riwayat
            $asetDesa->createHistory('delete', Auth::id(), 'Penghapusan aset');
            
            // Update desa last_updated_at
            $asetDesa->desa->updateLastUpdated();
            
            return redirect()->route('admin-desa.aset-desa.index')
                ->with('success', 'Data aset desa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat(AsetDesa $aset)
    {
        // Pastikan aset desa milik desa yang sama dengan user
        if ($aset->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $riwayat = RiwayatAsetDesa::where('aset_desa_id', $aset->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin-desa.aset-desa.riwayat', compact('aset', 'riwayat'));
    }

    public function exportPdf(Request $request)
    {
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        
        $query = AsetDesa::with('desa')
            ->where('desa_id', $desaId)
            ->current();
            
        // Filter berdasarkan kategori
        if ($request->filled('kategori_aset')) {
            $query->where('kategori_aset', $request->kategori_aset);
        }

        // Filter berdasarkan kondisi
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $asetDesas = $query->orderBy('nama_aset')->get();
        
        // Check if AsetDesaPdfExport class exists and is properly imported
        if (!class_exists('App\Exports\AsetDesaPdfExport')) {
            throw new \Exception('AsetDesaPdfExport class not found. Please ensure it exists and is properly imported.');
        }
        /** @var \App\Exports\AsetDesaPdfExport $export */
        $export = new AsetDesaPdfExport($asetDesas, $desa);
        $filename = $desa ? 'aset-desa-' . $desa->nama_desa . '.pdf' : 'aset-desa-' . date('Y-m-d') . '.pdf';
        return $export->download($filename);
    }

    /**
     * Export data aset desa ke Excel
     */
    public function exportExcel(Request $request)
    {
        $desaId = Auth::user()->desa_id;
        
        $filters = [
            'desa_id' => $desaId,
            'kategori_aset' => $request->kategori_aset,
            'kondisi' => $request->kondisi,
            'search' => $request->search,
        ];

        $filename = 'aset-desa-' . Auth::user()->desa->nama_desa . '-' . date('Y-m-d-H-i-s') . '.xlsx';
        
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
            
            return redirect()->route('admin-desa.aset-desa.index')
                ->with('success', 'Data aset desa berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('admin-desa.aset-desa.index')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}