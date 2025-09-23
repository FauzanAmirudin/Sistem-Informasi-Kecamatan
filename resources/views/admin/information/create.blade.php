@extends('layouts.admin')

@section('page-title', 'Tambah Informasi')
@section('page-subtitle', 'Tambah informasi baru')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.information.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                    Form Tambah Informasi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.information.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="judul" class="form-label">
                                    Judul <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('judul') is-invalid @enderror" 
                                       id="judul" 
                                       name="judul" 
                                       value="{{ old('judul') }}" 
                                       placeholder="Masukkan judul informasi"
                                       required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="kategori" class="form-label">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('kategori') is-invalid @enderror" 
                                        id="kategori" 
                                        name="kategori" 
                                        required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="pengumuman" {{ old('kategori') == 'pengumuman' ? 'selected' : '' }}>
                                        Pengumuman
                                    </option>
                                    <option value="informasi" {{ old('kategori') == 'informasi' ? 'selected' : '' }}>
                                        Informasi
                                    </option>
                                    <option value="berita" {{ old('kategori') == 'berita' ? 'selected' : '' }}>
                                        Berita
                                    </option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="konten" class="form-label">
                            Konten <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('konten') is-invalid @enderror" 
                                  id="konten" 
                                  name="konten" 
                                  rows="10" 
                                  placeholder="Masukkan konten informasi"
                                  required>{{ old('konten') }}</textarea>
                        @error('konten')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Gunakan format HTML untuk memformat teks jika diperlukan.
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                        Publikasikan Sekarang
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <strong>Draft:</strong> Informasi disimpan namun belum dipublikasikan<br>
                                    <strong>Publikasikan:</strong> Informasi langsung dipublikasikan dan dapat dilihat admin desa
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preview</label>
                                <div class="border rounded p-3 bg-light" style="min-height: 100px;">
                                    <div id="preview-content">
                                        <small class="text-muted">Preview akan muncul di sini...</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.information.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Simpan Informasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kontenTextarea = document.getElementById('konten');
    const previewContent = document.getElementById('preview-content');
    
    function updatePreview() {
        const content = kontenTextarea.value;
        if (content.trim()) {
            // Simple HTML preview (you might want to use a proper HTML sanitizer)
            previewContent.innerHTML = content.replace(/\n/g, '<br>');
        } else {
            previewContent.innerHTML = '<small class="text-muted">Preview akan muncul di sini...</small>';
        }
    }
    
    kontenTextarea.addEventListener('input', updatePreview);
    
    // Initial preview
    updatePreview();
});
</script>
@endpush
