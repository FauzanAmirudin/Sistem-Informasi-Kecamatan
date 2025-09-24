<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\PerangkatDesa;
use App\Models\RiwayatPerangkatDesa;
use App\Exports\PerangkatDesaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PerangkatDesaController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        $query = PerangkatDesa::with('desa')
            ->where('desa_id', $desaId)
            ->current();

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
        $desa = Desa::find($desaId);

        // Statistik
        $totalPerangkat = PerangkatDesa::where('desa_id', $desaId)->current()->count();
        $perangkatAktif = PerangkatDesa::where('desa_id', $desaId)->aktif()->count();
        $perangkatTidakAktif = PerangkatDesa::where('desa_id', $desaId)->current()->where('status', 'tidak_aktif')->count();

        return view('admin-desa.perangkat-desa.index', compact(
            'perangkatDesas', 
            'desa', 
            'totalPerangkat', 
            'perangkatAktif', 
            'perangkatTidakAktif'
        ));
    }

    public function create()
    {
        $desa = Desa::find(Auth::user()->desa_id);
        return view('admin-desa.perangkat-desa.create', compact('desa'));
    }

    public function store(Request $request)
    {
        $request->validate([
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
        $data['desa_id'] = Auth::user()->desa_id;
        $data['updated_by'] = Auth::id();

        // Upload SK
        if ($request->hasFile('sk_pengangkatan')) {
            $data['sk_pengangkatan'] = $request->file('sk_pengangkatan')
                ->store('sk-perangkat', 'uploads');
        }

        $perangkat = PerangkatDesa::create($data);

        // Update desa last_updated_at
        $perangkat->desa->updateLastUpdated();

        return redirect()->route('admin-desa.perangkat-desa.index')
            ->with('success', 'Data perangkat desa berhasil ditambahkan.');
    }

    public function show(PerangkatDesa $perangkatDesa)
    {
        // Pastikan perangkat desa milik desa yang sama dengan user
        if ($perangkatDesa->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $perangkatDesa->load('desa', 'updatedBy');
        return view('admin-desa.perangkat-desa.show', compact('perangkatDesa'));
    }

    public function edit(PerangkatDesa $perangkatDesa)
    {
        // Pastikan perangkat desa milik desa yang sama dengan user
        if ($perangkatDesa->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $desa = Desa::find(Auth::user()->desa_id);
        return view('admin-desa.perangkat-desa.edit', compact('perangkatDesa', 'desa'));
    }

    public function update(Request $request, PerangkatDesa $perangkatDesa)
    {
        try {
            // Debug untuk melihat request yang masuk
            Log::info('Update Perangkat Desa - Request:', [
                'request_all' => $request->all(),
                'files' => $request->allFiles(),
                'perangkat_desa_id' => $perangkatDesa->id,
                'user_desa_id' => Auth::user()->desa_id
            ]);

            // Pastikan perangkat desa milik desa yang sama dengan user
            if ($perangkatDesa->desa_id != Auth::user()->desa_id) {
                Log::error('Update Perangkat Desa - Unauthorized:', [
                    'perangkat_desa_id' => $perangkatDesa->id,
                    'perangkat_desa_desa_id' => $perangkatDesa->desa_id,
                    'user_desa_id' => Auth::user()->desa_id
                ]);
                abort(403, 'Unauthorized action.');
            }
            // Debug validasi
            Log::info('Update Perangkat Desa - Before Validation');
            
            // Validasi request
            try {
                $validated = $request->validate([
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
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $e->errors()
                    ], 422);
                }
                throw $e;
            }

            // Debug setelah validasi
            Log::info('Update Perangkat Desa - After Validation', [
                'validated_data' => $validated
            ]);

            // Mulai transaksi database
            DB::beginTransaction();
            
            // Debug untuk melihat data yang diterima
            Log::info('Update Perangkat Desa - Before Update:', [
                'request_data' => $request->all(),
                'perangkat_desa' => $perangkatDesa->toArray()
            ]);
            
            $data = $request->except('_token', '_method');
            $data['desa_id'] = Auth::user()->desa_id; // Pastikan desa_id tetap milik user
            $data['updated_by'] = Auth::id();
            $data['update_reason'] = $request->update_reason; // Pastikan update_reason tersimpan

            // Upload SK baru
            if ($request->hasFile('sk_pengangkatan')) {
                // Hapus file lama
                if ($perangkatDesa->sk_pengangkatan) {
                    Storage::disk('uploads')->delete($perangkatDesa->sk_pengangkatan);
                }
                $data['sk_pengangkatan'] = $request->file('sk_pengangkatan')
                    ->store('sk-perangkat', 'uploads');
            }

            // Simpan alasan update
            $updateReason = $request->update_reason;
            
            // Debug sebelum update
            Log::info('Update Perangkat Desa - Update Data:', [
                'data_to_update' => $data
            ]);

            try {
                // Update data perangkat desa
                $updated = $perangkatDesa->update($data);
                
                Log::info('Update Perangkat Desa - After Update:', [
                    'success' => $updated,
                    'updated_data' => $perangkatDesa->fresh()->toArray()
                ]);

                // Buat riwayat perubahan secara manual
                $perangkatDesa->createHistory('updated', Auth::id(), $updateReason);
            } catch (\Exception $e) {
                Log::error('Update Perangkat Desa - Update Failed:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            // Update desa last_updated_at
            $perangkatDesa->desa->updateLastUpdated();
            
            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Jika request adalah AJAX, berikan response JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data perangkat desa berhasil diperbarui.',
                    'redirect' => route('admin-desa.perangkat-desa.index')
                ]);
            }

            // Jika bukan AJAX, redirect seperti biasa
            return redirect()->route('admin-desa.perangkat-desa.index')
                ->with('success', 'Data perangkat desa berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . collect($e->errors())->first()[0]);
        } catch (\Exception $e) {
            DB::rollback();
            // Log error untuk debugging
            Log::error('Error Update Perangkat Desa: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            // Jika request adalah AJAX, berikan response JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function destroy(PerangkatDesa $perangkatDesa): \Illuminate\Http\RedirectResponse
    {
        try {
            // Pastikan perangkat desa milik desa yang sama dengan user
            if ($perangkatDesa->desa_id != Auth::user()->desa_id) {
                Log::error('Delete Perangkat Desa - Unauthorized:', [
                    'perangkat_desa_id' => $perangkatDesa->id,
                    'perangkat_desa_desa_id' => $perangkatDesa->desa_id,
                    'user_desa_id' => Auth::user()->desa_id
                ]);
                abort(403, 'Unauthorized action.');
            }

            DB::beginTransaction();
            
            $desa = $perangkatDesa->desa;
            
            // Hapus file SK jika ada
            if ($perangkatDesa->sk_pengangkatan) {
                Storage::disk('uploads')->delete($perangkatDesa->sk_pengangkatan);
            }

            $perangkatDesa->delete();
            
            // Update desa last_updated_at
            $desa->updateLastUpdated();

            DB::commit();

            return redirect()->route('admin-desa.perangkat-desa.index')
                ->with('success', 'Data perangkat desa berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error Delete Perangkat Desa: ' . $e->getMessage(), [
                'exception' => $e,
                'perangkat_desa_id' => $perangkatDesa->id
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
        }
    }

    public function riwayat(PerangkatDesa $perangkat): \Illuminate\View\View
    {
        // Pastikan perangkat desa milik desa yang sama dengan user
        if ($perangkat->desa_id != Auth::user()->desa_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $riwayat = RiwayatPerangkatDesa::where('perangkat_desa_id', $perangkat->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin-desa.perangkat-desa.riwayat', compact('perangkat', 'riwayat'));
    }

    public function exportExcel(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $desaId = Auth::user()->desa_id;
        $desa = Desa::find($desaId);
        
        return Excel::download(new PerangkatDesaExport($desaId), 'perangkat-desa-' . $desa->nama_desa . '.xlsx');
    }
}