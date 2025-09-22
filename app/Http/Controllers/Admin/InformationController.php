<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informations = Information::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.information.index', compact('informations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.information.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:pengumuman,informasi,berita',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        Information::create($data);

        return redirect()->route('admin.information.index')
            ->with('success', 'Informasi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Information $information)
    {
        $information->load('creator');
        return view('admin.information.show', compact('information'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Information $information)
    {
        return view('admin.information.edit', compact('information'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Information $information)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:pengumuman,informasi,berita',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->all();
        
        // Jika status berubah dari draft ke published
        if ($information->status === 'draft' && $request->status === 'published') {
            $data['published_at'] = now();
        }
        // Jika status berubah dari published ke draft
        elseif ($information->status === 'published' && $request->status === 'draft') {
            $data['published_at'] = null;
        }

        $information->update($data);

        return redirect()->route('admin.information.index')
            ->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $information)
    {
        $information->delete();

        return redirect()->route('admin.information.index')
            ->with('success', 'Informasi berhasil dihapus.');
    }

    /**
     * Toggle status informasi
     */
    public function toggleStatus(Information $information)
    {
        if ($information->status === 'draft') {
            $information->update([
                'status' => 'published',
                'published_at' => now()
            ]);
            $message = 'Informasi berhasil dipublikasikan.';
        } else {
            $information->update([
                'status' => 'draft',
                'published_at' => null
            ]);
            $message = 'Informasi berhasil diubah ke draft.';
        }

        return redirect()->back()->with('success', $message);
    }
}
