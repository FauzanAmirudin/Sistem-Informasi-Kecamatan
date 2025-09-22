@extends('layouts.admin')

@section('page-title', 'Detail Informasi')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.information.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin.information.edit', $information) }}" class="btn btn-outline-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <form action="{{ route('admin.information.toggle-status', $information) }}" 
          method="POST" class="d-inline">
        @csrf
        <button type="submit" 
                class="btn btn-outline-{{ $information->status === 'published' ? 'secondary' : 'success' }}">
            <i class="fas fa-{{ $information->status === 'published' ? 'eye-slash' : 'eye' }} me-1"></i>
            {{ $information->status === 'published' ? 'Ubah ke Draft' : 'Publikasikan' }}
        </button>
    </form>
</div>
@endsection

@section('admin-content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="fas fa-newspaper me-2 text-primary"></i>
                            Detail Informasi
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info">{{ ucfirst($information->kategori) }}</span>
                            <span class="badge bg-{{ $information->status_badge }}">
                                {{ $information->status_text }}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">
                            Dibuat: {{ $information->created_at->format('d/m/Y H:i') }}<br>
                            @if($information->published_at)
                                Dipublikasikan: {{ $information->published_at->format('d/m/Y H:i') }}
                            @else
                                Status: Draft
                            @endif
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h2 class="text-dark fw-bold mb-3">{{ $information->judul }}</h2>
                    
                    <div class="border-top border-bottom py-3 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <span class="fw-bold">Dibuat oleh:</span>
                                    <span class="ms-2">{{ $information->creator->name }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <span class="fw-bold">Tanggal dibuat:</span>
                                    <span class="ms-2">{{ $information->created_at->format('d F Y, H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-edit text-primary me-2"></i>
                                    <span class="fw-bold">Terakhir diupdate:</span>
                                    <span class="ms-2">{{ $information->updated_at->format('d F Y, H:i') }}</span>
                                </div>
                                @if($information->published_at)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-eye text-success me-2"></i>
                                        <span class="fw-bold">Dipublikasikan:</span>
                                        <span class="ms-2">{{ $information->published_at->format('d F Y, H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="content-section">
                    <h5 class="text-dark mb-3">
                        <i class="fas fa-file-alt me-2"></i>
                        Konten Informasi
                    </h5>
                    <div class="border rounded p-4 bg-light">
                        <div class="information-content">
                            {!! nl2br(e($information->konten)) !!}
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-1"></i>
                            Informasi Status
                        </h6>
                        @if($information->status === 'published')
                            <p class="mb-0">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Informasi ini sudah dipublikasikan dan dapat dilihat oleh admin desa di halaman informasi mereka.
                            </p>
                        @else
                            <p class="mb-0">
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                Informasi ini masih dalam status draft dan belum dapat dilihat oleh admin desa.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('admin.information.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.information.edit', $information) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            Edit Informasi
                        </a>
                        <form action="{{ route('admin.information.destroy', $information) }}" 
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.information-content {
    line-height: 1.6;
    font-size: 1rem;
}

.information-content p {
    margin-bottom: 1rem;
}

.information-content h1,
.information-content h2,
.information-content h3,
.information-content h4,
.information-content h5,
.information-content h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: bold;
}

.information-content ul,
.information-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.information-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6c757d;
}
</style>
@endpush
