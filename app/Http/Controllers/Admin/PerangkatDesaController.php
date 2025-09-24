<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerangkatDesa;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerangkatDesaExport;

class PerangkatDesaController extends Controller
{
    public function index(Request $request)
    {
        $query = PerangkatDesa::with('desa')->current();

        // Filter berdasarkan desa
        if ($request->filled('desa_id')) {
            $query->where('desa_id', $request->desa_id);
        }

        // Filter berdasarkan jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', 'like', '%' . $request->jabatan . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $perangkatDesas = $query->orderBy('jabatan')->orderBy('nama_lengkap')->paginate(20);
        $desas = Desa::orderBy('nama_desa')->get();

        // Statistik
        $totalPerangkat = PerangkatDesa::current()->count();
        $perangkatAktif = PerangkatDesa::aktif()->count();
        $perangkatTidakAktif = PerangkatDesa::current()->where('status', 'tidak_aktif')->count();

        return view('admin.perangkat-desa.index', compact(
            'perangkatDesas', 
            'desas', 
            'totalPerangkat', 
            'perangkatAktif', 
            'perangkatTidakAktif'
        ));
    }

    public function create()
    {
        $desas = Desa::where('status', 'aktif')->orderBy('nama_desa')->get();
        return view('admin.perangkat-desa.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:perangkat_desas',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_terakhir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'tanggal_mulai_tugas' => 'required|date',
            'tanggal_akhir_tugas' => 'nullable|date|after:tanggal_mulai_tugas',
            'sk_pengangkatan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jobdesk' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::id();

        // Upload SK
        if ($request->hasFile('sk_pengangkatan')) {
            $data['sk_pengangkatan'] = $request->file('sk_pengangkatan')
                ->store('sk-perangkat', 'uploads');
        }

        $perangkat = PerangkatDesa::create($data);

        // Update desa last_updated_at
        $perangkat->desa->updateLastUpdated();

        return redirect()->route('admin.perangkat-desa.index')
            ->with('success', 'Data perangkat desa berhasil ditambahkan.');
    }

    public function show(PerangkatDesa $perangkatDesa)
    {
        $perangkatDesa->load('desa', 'updatedBy');
        return view('admin.perangkat-desa.show', compact('perangkatDesa'));
    }

    public function edit(PerangkatDesa $perangkatDesa)
    {
        $desas = Desa::where('status', 'aktif')->orderBy('nama_desa')->get();
        return view('admin.perangkat-desa.edit', compact('perangkatDesa', 'desas'));
    }

    public function update(Request $request, PerangkatDesa $perangkatDesa)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:perangkat_desas,nik,' . $perangkatDesa->id,
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'pendidikan_terakhir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:20',
            'tanggal_mulai_tugas' => 'required|date',
            'tanggal_akhir_tugas' => 'nullable|date|after:tanggal_mulai_tugas',
            'sk_pengangkatan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jobdesk' => 'nullable|string',
            'status' => 'required|in:aktif,tidak_aktif',
            'update_reason' => 'required|string|max:255',
        ]);

        try {
            // Mulai transaksi database
            \Illuminate\Support\Facades\DB::beginTransaction();
            
            // Simpan alasan update terlebih dahulu
            $updateReason = $request->input('update_reason');
            
            // Siapkan data untuk update
            $data = $request->except('_token', '_method', 'update_reason');
            $data['updated_by'] = Auth::id();
            $data['update_reason'] = $updateReason;

            // Upload SK baru
            if ($request->hasFile('sk_pengangkatan')) {
                // Hapus file lama
                if ($perangkatDesa->sk_pengangkatan) {
                    Storage::disk('uploads')->delete($perangkatDesa->sk_pengangkatan);
                }
                $data['sk_pengangkatan'] = $request->file('sk_pengangkatan')
                    ->store('sk-perangkat', 'uploads');
            }

            // Update data perangkat desa
            $perangkatDesa->update($data);

            // Update desa last_updated_at
            $perangkatDesa->desa->updateLastUpdated();
            
            // Commit transaksi jika semua operasi berhasil
            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.perangkat-desa.show', $perangkatDesa)
                ->with('success', 'Data perangkat desa berhasil diperbarui.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            \Illuminate\Support\Facades\DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(PerangkatDesa $perangkatDesa)
    {
        $desa = $perangkatDesa->desa;
        
        // Hapus file SK jika ada
        if ($perangkatDesa->sk_pengangkatan) {
            Storage::disk('uploads')->delete($perangkatDesa->sk_pengangkatan);
        }

        $perangkatDesa->delete();
        
        // Update desa last_updated_at
        $desa->updateLastUpdated();

        return redirect()->route('admin.perangkat-desa.index')
            ->with('success', 'Data perangkat desa berhasil dihapus.');
    }

    public function riwayat(PerangkatDesa $perangkat)
    {
        $riwayat = $perangkat->riwayat()->with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.perangkat-desa.riwayat', compact('perangkat', 'riwayat'));
    }
    
    /**
     * Export data perangkat desa ke Excel
     */
    public function exportExcel(Request $request)
    {
        $filename = 'data-perangkat-desa-' . date('YmdHis') . '.xlsx';
        
        return Excel::download(
            new PerangkatDesaExport(
                $request->desa_id,
                $request->jabatan,
                $request->status
            ),
            $filename
        );
    }
    
    /**
     * Download SK Pengangkatan Perangkat Desa
     */
    public function downloadSK(PerangkatDesa $perangkatDesa)
    {
        if (!$perangkatDesa->sk_pengangkatan) {
            return back()->with('error', 'File SK Pengangkatan tidak ditemukan.');
        }
        
        $path = storage_path('app/public/' . $perangkatDesa->sk_pengangkatan);
        
        if (!file_exists($path)) {
            return back()->with('error', 'File SK Pengangkatan tidak ditemukan di server.');
        }
        
        $extension = pathinfo($perangkatDesa->sk_pengangkatan, PATHINFO_EXTENSION);
        $fileName = 'SK_Pengangkatan_' . str_replace(' ', '_', $perangkatDesa->nama_lengkap) . '_' . $perangkatDesa->jabatan . '.' . $extension;
        
        return response()->download($path, $fileName);
    }
}