<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index()
    {
        $dokumens = Dokumen::with(['desa', 'uploader'])->paginate(20);
        return view('admin.dokumen.index', compact('dokumens'));
    }

    public function create()
    {
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.dokumen.create', compact('desas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:surat,laporan,peraturan,pedoman,lainnya',
            'is_public' => 'boolean',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $uploadedCount = 0;
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dokumen', $fileName, 'public');
                
                Dokumen::create([
                    'desa_id' => $request->desa_id,
                    'nama_dokumen' => $request->nama_dokumen,
                    'deskripsi' => $request->deskripsi,
                    'kategori' => $request->kategori,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                    'is_public' => $request->has('is_public'),
                ]);
                
                $uploadedCount++;
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            $message = $uploadedCount . ' dokumen berhasil diupload';
            if (count($errors) > 0) {
                $message .= ', namun terdapat ' . count($errors) . ' error';
                session()->flash('errors', $errors);
            }
            return redirect()->route('admin.dokumen.index')->with('success', $message);
        } else {
            return redirect()->route('admin.dokumen.create')
                ->with('error', 'Gagal mengupload dokumen: ' . implode(', ', $errors))
                ->withInput();
        }
    }

    public function show(Dokumen $dokuman)
    {
        return view('admin.dokumen.show', compact('dokuman'));
    }

    public function edit(Dokumen $dokuman)
    {
        $desas = Desa::orderBy('nama_desa')->get();
        return view('admin.dokumen.edit', compact('dokuman', 'desas'));
    }

    public function update(Request $request, Dokumen $dokuman)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:surat,laporan,peraturan,pedoman,lainnya',
            'is_public' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $data = [
            'desa_id' => $request->desa_id,
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'is_public' => $request->has('is_public'),
        ];

        // Jika ada file baru yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Hapus file lama jika ada
            if ($dokuman->file_path && Storage::disk('public')->exists($dokuman->file_path)) {
                Storage::disk('public')->delete($dokuman->file_path);
            }
            
            // Upload file baru
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen', $fileName, 'public');
            
            // Update data file
            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
        }

        $dokuman->update($data);

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Dokumen $dokuman)
    {
        // Hapus file dari storage jika ada
        if ($dokuman->file_path && Storage::disk('public')->exists($dokuman->file_path)) {
            Storage::disk('public')->delete($dokuman->file_path);
        }
        
        $dokuman->delete();
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(Dokumen $dokuman)
    {
        // Cek apakah file ada di storage
        if (!$dokuman->file_path || !Storage::disk('public')->exists($dokuman->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        // Catat aktivitas download (opsional)
        // Log::info('Dokumen diunduh: ' . $dokuman->nama_dokumen . ' oleh ' . Auth::user()->name);

        // Ambil ekstensi file dari MIME type
        $extension = '';
        $contentType = $dokuman->file_type;
        
        if (strpos($contentType, 'pdf') !== false) {
            $extension = '.pdf';
        } elseif (strpos($contentType, 'jpeg') !== false || strpos($contentType, 'jpg') !== false) {
            $extension = '.jpg';
        } elseif (strpos($contentType, 'png') !== false) {
            $extension = '.png';
        } elseif (strpos($contentType, 'word') !== false || strpos($contentType, 'docx') !== false) {
            $extension = '.docx';
        } elseif (strpos($contentType, 'excel') !== false || strpos($contentType, 'xlsx') !== false) {
            $extension = '.xlsx';
        } elseif (strpos($contentType, 'powerpoint') !== false || strpos($contentType, 'pptx') !== false) {
            $extension = '.pptx';
        }

        // Buat nama file yang aman untuk didownload
        $downloadName = str_replace(' ', '_', $dokuman->nama_dokumen) . $extension;

        // Return file untuk didownload
        return response()->download(storage_path('app/public/' . $dokuman->file_path), $downloadName);
    }
}