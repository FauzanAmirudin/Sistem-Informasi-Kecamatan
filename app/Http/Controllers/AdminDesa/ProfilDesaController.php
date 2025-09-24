<?php

namespace App\Http\Controllers\AdminDesa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfilDesaController extends Controller
{
    /**
     * Display the profile of the village.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {   
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        return view('admin-desa.profil.index', compact('desa'));
    }

    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        return view('admin-desa.profil.edit', compact('desa'));
    }

    /**
     * Update the profile in storage.
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
            'kode_desa' => 'required|string|max:50',
            'kepala_desa' => 'nullable|string|max:255',
            'sk_kepala_desa' => 'nullable|file|mimes:pdf|max:5120',
            'alamat' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'luas_wilayah' => 'nullable|numeric|min:0',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'komoditas_unggulan' => 'nullable|string',
            'kondisi_sosial_ekonomi' => 'nullable|string',
            'monografi_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->only([
            'nama_desa',
            'kode_desa',
            'kepala_desa',
            'alamat',
            'kode_pos',
            'luas_wilayah',
            'latitude',
            'longitude',
            'komoditas_unggulan',
            'kondisi_sosial_ekonomi',
        ]);

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
        
        // Refresh data desa dari database untuk memastikan data terbaru dimuat
        $desa->refresh();

        return redirect()->route('admin-desa.profil.index')
            ->with('success', 'Profil desa berhasil diperbarui.');
    }

    /**
     * Download monografi file.
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadMonografi()
    {
        // Mendapatkan ID desa dari user yang login
        $desaId = Auth::user()->desa_id;
        
        // Mendapatkan data desa
        $desa = Desa::findOrFail($desaId);
        
        if (!$desa->monografi_file) {
            return redirect()->back()->with('error', 'File monografi belum diunggah.');
        }
        
return response()->download(storage_path('app/public/' . $desa->monografi_file), 'Monografi_' . $desa->nama_desa . '.pdf');
    }
}