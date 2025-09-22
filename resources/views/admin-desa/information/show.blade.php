@extends('layouts.admin-desa')

@section('page-title', 'Detail Informasi')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.information.index') }}" class="btn btn-outline-secondary">
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
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="fas fa-newspaper me-2 text-primary"></i>
                            {{ $information->judul }}
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info">{{ ucfirst($information->kategori) }}</span>
                            <span class="badge bg-success">Dipublikasikan</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">
                            {{ $information->published_at->format('d F Y, H:i') }}<br>
                            <i class="fas fa-user me-1"></i>{{ $information->creator->name }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="information-content">
                    {!! nl2br(e($information->konten)) !!}
                </div>
                
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <strong>Dipublikasikan:</strong> {{ $information->published_at->format('d F Y, H:i') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                <strong>Oleh:</strong> {{ $information->creator->name }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="{{ route('admin-desa.information.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Informasi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.information-content {
    line-height: 1.8;
    font-size: 1rem;
    color: #333;
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
    color: #2c3e50;
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
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
}

.information-content strong {
    font-weight: 600;
    color: #2c3e50;
}
</style>
@endpush
