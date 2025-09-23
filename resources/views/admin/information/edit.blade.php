@extends('layouts.admin')

@section('page-title', 'Edit Informasi')
@section('page-subtitle', 'Edit informasi')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.information.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin.information.show', $information) }}" class="btn btn-outline-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
</div>
@endsection

@section('admin-content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>
                    Form Edit Informasi
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.information.update', $information) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                       value="{{ old('judul', $information->judul) }}" 
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
                                    <option value="pengumuman" {{ old('kategori', $information->kategori) == 'pengumuman' ? 'selected' : '' }}>
                                        Pengumuman
                                    </option>
                                    <option value="informasi" {{ old('kategori', $information->kategori) == 'informasi' ? 'selected' : '' }}>
                                        Informasi
                                    </option>
                                    <option value="berita" {{ old('kategori', $information->kategori) == 'berita' ? 'selected' : '' }}>
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
                                  required>{{ old('konten', $information->konten) }}</textarea>
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
                                    <option value="draft" {{ old('status', $information->status) == 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>
                                    <option value="published" {{ old('status', $information->status) == 'published' ? 'selected' : '' }}>
                                        Publikasikan
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <strong>Draft:</strong> Informasi disimpan namun belum dipublikasikan<br>
                                    <strong>Publikasikan:</strong> Informasi dipublikasikan dan dapat dilihat admin desa
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
                    
                    <!-- Informasi tambahan -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Informasi Tambahan
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Dibuat oleh:</strong> {{ $information->creator->name }}<br>
                                            <strong>Tanggal dibuat:</strong> {{ $information->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Terakhir diupdate:</strong> {{ $information->updated_at->format('d/m/Y H:i') }}<br>
                                            @if($information->published_at)
                                                <strong>Tanggal publikasi:</strong> {{ $information->published_at->format('d/m/Y H:i') }}
                                            @else
                                                <strong>Status:</strong> Belum dipublikasikan
                                            @endif
                                        </small>
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
                                Update Informasi
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
