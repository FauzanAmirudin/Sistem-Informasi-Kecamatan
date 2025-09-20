@extends('layouts.admin')

@section('page-title', 'Detail Dokumen')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.dokumen.edit', $dokuman) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin-desa.dokumen.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <!-- Detail Dokumen -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Dokumen
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Nama Dokumen</strong></td>
                        <td>{{ $dokuman->nama_dokumen }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kategori</strong></td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($dokuman->kategori) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Ukuran File</strong></td>
                        <td>{{ $dokuman->file_size_formatted }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipe File</strong></td>
                        <td>
                            @php
                                $extension = pathinfo($dokuman->file_path, PATHINFO_EXTENSION);
                            @endphp
                            <span class="badge bg-secondary">{{ strtoupper($extension) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Diupload Oleh</strong></td>
                        <td>{{ $dokuman->uploader->name ?? 'Tidak diketahui' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Upload</strong></td>
                        <td>{{ $dokuman->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terakhir Diubah</strong></td>
                        <td>{{ $dokuman->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            <span class="badge {{ $dokuman->is_public ? 'bg-success' : 'bg-secondary' }}">
                                {{ $dokuman->is_public ? 'Publik' : 'Private' }}
                            </span>
                        </td>
                    </tr>
                </table>

                @if($dokuman->deskripsi)
                <div class="mt-3">
                    <h6>Deskripsi:</h6>
                    <p class="text-muted">{{ $dokuman->deskripsi }}</p>
                </div>
                @endif

                <div class="d-grid gap-2 mt-4">
                    <a href="{{ route('admin-desa.dokumen.download', $dokuman) }}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i>
                        Download Dokumen
                    </a>
                    <form action="{{ route('admin-desa.dokumen.destroy', $dokuman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash-alt me-1"></i>
                            Hapus Dokumen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Dokumen -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file me-2"></i>
                    Preview Dokumen
                </h5>
            </div>
            <div class="card-body">
                @php
                    $extension = pathinfo($dokuman->file_path, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                    $isPdf = strtolower($extension) === 'pdf';
                @endphp

                @if($isImage)
                    <div class="text-center">
                        <img src="{{ Storage::url($dokuman->file_path) }}" alt="{{ $dokuman->nama_dokumen }}" class="img-fluid rounded shadow-sm" style="max-height: 600px;">
                    </div>
                @elseif($isPdf)
                    <div class="ratio ratio-16x9" style="height: 600px;">
                        <iframe src="{{ Storage::url($dokuman->file_path) }}" allowfullscreen></iframe>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file fa-5x text-muted mb-3"></i>
                        <h4>Preview tidak tersedia</h4>
                        <p class="text-muted">Format file tidak mendukung preview langsung di browser.</p>
                        <a href="{{ route('admin-desa.dokumen.download', $dokuman) }}" class="btn btn-primary mt-3">
                            <i class="fas fa-download me-1"></i>
                            Download untuk melihat
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection