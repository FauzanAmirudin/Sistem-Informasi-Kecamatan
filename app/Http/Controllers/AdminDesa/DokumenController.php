<?php

namespace App\Http\Controllers\AdminDesa;

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
        $desa_id = Auth::user()->desa_id;
        $dokumens = Dokumen::where('desa_id', $desa_id)
            ->when(request('kategori'), function($query) {
                return $query->where('kategori', request('kategori'));
            })
            ->when(request('search'), function($query) {
                return $query->where(function($q) {
                    $q->where('nama_dokumen', 'like', '%' . request('search') . '%')
                      ->orWhere('deskripsi', 'like', '%' . request('search') . '%');
                });
            })
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin-desa.dokumen.index', compact('dokumens'));
    }

    public function create()
    {
        return view('admin-desa.dokumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:surat,laporan,peraturan,pedoman,lainnya',
            'is_public' => 'boolean',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $desa_id = Auth::user()->desa_id;
        $uploadedCount = 0;
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('dokumen/' . $desa_id, $fileName, 'public');
                
                Dokumen::create([
                    'desa_id' => $desa_id,
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
            return redirect()->route('admin-desa.dokumen.index')->with('success', $message);
        } else {
            return redirect()->route('admin-desa.dokumen.create')
                ->with('error', 'Gagal mengupload dokumen: ' . implode(', ', $errors))
                ->withInput();
        }
    }

    public function show(Dokumen $dokuman)
    {
        // Pastikan dokumen milik desa yang sama dengan user
        if ($dokuman->desa_id != Auth::user()->desa_id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini');
        }

        return view('admin-desa.dokumen.show', compact('dokuman'));
    }

    public function edit(Dokumen $dokuman)
    {
        // Pastikan dokumen milik desa yang sama dengan user
        if ($dokuman->desa_id != Auth::user()->desa_id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini');
        }

        return view('admin-desa.dokumen.edit', compact('dokuman'));
    }

    public function update(Request $request, Dokumen $dokuman)
    {
        // Pastikan dokumen milik desa yang sama dengan user
        if ($dokuman->desa_id != Auth::user()->desa_id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini');
        }

        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'required|in:surat,laporan,peraturan,pedoman,lainnya',
            'is_public' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $data = [
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
            $filePath = $file->storeAs('dokumen/' . Auth::user()->desa_id, $fileName, 'public');
            
            // Update data file
            $data['file_path'] = $filePath;
            $data['file_type'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
        }

        $dokuman->update($data);

        return redirect()->route('admin-desa.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Dokumen $dokuman)
    {
        // Pastikan dokumen milik desa yang sama dengan user
        if ($dokuman->desa_id != Auth::user()->desa_id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini');
        }

        // Hapus file dari storage
        if ($dokuman->file_path && Storage::disk('public')->exists($dokuman->file_path)) {
            Storage::disk('public')->delete($dokuman->file_path);
        }

        $dokuman->delete();
        return redirect()->route('admin-desa.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(Dokumen $dokuman)
    {
        // Pastikan dokumen milik desa yang sama dengan user atau dokumen bersifat publik
        if ($dokuman->desa_id != Auth::user()->desa_id && !$dokuman->is_public) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini');
        }

        // Cek apakah file ada di storage
        if (!$dokuman->file_path || !Storage::disk('public')->exists($dokuman->file_path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

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