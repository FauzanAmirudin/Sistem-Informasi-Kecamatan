<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::with(['penduduks', 'perangkatDesas'])
            ->findOrFail($desaId);
        
        // Statistik
        $totalPenduduk = $desa->penduduks->count();
        $pendudukPria = $desa->penduduks->where('jenis_kelamin', 'L')->count();
        $pendudukWanita = $desa->penduduks->where('jenis_kelamin', 'P')->count();
        $perangkatAktif = $desa->perangkatDesas->where('status', 'aktif')->count();
        
        return view('admin-desa.desa.index', compact(
            'desa', 
            'totalPenduduk', 
            'pendudukPria', 
            'pendudukWanita', 
            'perangkatAktif'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        return view('admin-desa.desa.edit', compact('desa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kepala_desa' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'luas_wilayah' => 'nullable|numeric|min:0',
            'komoditas_unggulan' => 'nullable|string',
            'kondisi_sosial_ekonomi' => 'nullable|string',
            'sk_kepala_desa' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'monografi_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->except(['_token', '_method']);

        // Upload SK Kepala Desa
        if ($request->hasFile('sk_kepala_desa')) {
            // Hapus file lama
            if ($desa->sk_kepala_desa) {
                Storage::disk('public')->delete($desa->sk_kepala_desa);
            }
            $data['sk_kepala_desa'] = $request->file('sk_kepala_desa')
                ->store('sk-kepala-desa', 'public');
        }

        // Upload Monografi
        if ($request->hasFile('monografi_file')) {
            // Hapus file lama
            if ($desa->monografi_file) {
                Storage::disk('public')->delete($desa->monografi_file);
            }
            $data['monografi_file'] = $request->file('monografi_file')
                ->store('monografi', 'public');
        }

        $data['last_updated_at'] = now();

        $desa->update($data);

        return redirect()->route('admin-desa.desa.index')
            ->with('success', 'Data desa berhasil diperbarui.');
    }
    
    /**
     * Download SK Kepala Desa
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSK()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        // Cek apakah file ada di storage
        if (!$desa->sk_kepala_desa || !Storage::disk('public')->exists($desa->sk_kepala_desa)) {
            return redirect()->back()->with('error', 'File SK Kepala Desa tidak ditemukan.');
        }
        
        // Ambil ekstensi file dari path
        $extension = pathinfo($desa->sk_kepala_desa, PATHINFO_EXTENSION);
        
        // Buat nama file yang aman untuk didownload
        $downloadName = 'SK_Kepala_Desa_' . str_replace(' ', '_', $desa->nama_desa) . '.' . $extension;
        
        // Return file untuk didownload
        return response()->download(storage_path('app/public/' . $desa->sk_kepala_desa), $downloadName);
    }
    
    /**
     * Download Monografi Desa
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadMonografi()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        // Cek apakah file ada di storage
        if (!$desa->monografi_file || !Storage::disk('public')->exists($desa->monografi_file)) {
            return redirect()->back()->with('error', 'File Monografi Desa tidak ditemukan.');
        }
        
        // Ambil ekstensi file dari path
        $extension = pathinfo($desa->monografi_file, PATHINFO_EXTENSION);
        
        // Buat nama file yang aman untuk didownload
        $downloadName = 'Monografi_Desa_' . str_replace(' ', '_', $desa->nama_desa) . '.' . $extension;
        
        // Return file untuk didownload
        return response()->download(storage_path('app/public/' . $desa->monografi_file), $downloadName);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa dengan relasi
        $desa = Desa::with(['penduduks', 'perangkatDesas', 'asetDesas', 'asetTanahWargas'])
            ->findOrFail($desaId);
        
        return view('admin-desa.desa.show', compact('desa'));
    }
}