@extends('layouts.admin')

@section('page-title', 'Detail Dokumen')
@section('page-subtitle', 'Detail dokumen')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.dokumen.edit', $dokuman) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
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
                        <td><strong>Desa</strong></td>
                        <td>{{ $dokuman->desa->nama_desa ?? 'Tidak ada' }}</td>
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
                    <a href="{{ route('admin.dokumen.download', $dokuman) }}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i>
                        Download Dokumen
                    </a>
                    <form action="{{ route('admin.dokumen.destroy', $dokuman) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
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

    <!-- Document Viewer -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Preview Dokumen
                </h5>
            </div>
            <div class="card-body p-0">
                @php
                    $extension = pathinfo($dokuman->file_path, PATHINFO_EXTENSION);
                    $isPdf = strtolower($extension) === 'pdf';
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                @endphp

                @if($isPdf)
                    <div class="ratio ratio-16x9" style="min-height: 600px;">
                        <iframe src="{{ $dokuman->file_url }}" allowfullscreen></iframe>
                    </div>
                @elseif($isImage)
                    <div class="text-center p-3">
                        <img src="{{ $dokuman->file_url }}" alt="{{ $dokuman->nama_dokumen }}" class="img-fluid" style="max-height: 600px;">
                    </div>
                @else
                    <div class="text-center p-5">
                        <div class="mb-4">
                            @php
                                $icon = 'fa-file';
                                $fileType = $dokuman->file_type;
                                
                                if (strpos($fileType, 'word') !== false) {
                                    $icon = 'fa-file-word';
                                } elseif (strpos($fileType, 'excel') !== false || strpos($fileType, 'spreadsheet') !== false) {
                                    $icon = 'fa-file-excel';
                                } elseif (strpos($fileType, 'powerpoint') !== false || strpos($fileType, 'presentation') !== false) {
                                    $icon = 'fa-file-powerpoint';
                                } elseif (strpos($fileType, 'zip') !== false || strpos($fileType, 'archive') !== false) {
                                    $icon = 'fa-file-archive';
                                } elseif (strpos($fileType, 'text') !== false) {
                                    $icon = 'fa-file-alt';
                                }
                            @endphp
                            <i class="fas {{ $icon }} fa-5x text-muted"></i>
                        </div>
                        <h5>{{ basename($dokuman->file_path) }}</h5>
                        <p class="text-muted">Preview tidak tersedia untuk tipe file ini.</p>
                        <p>Silakan download file untuk melihat isinya.</p>
                        <a href="{{ route('admin.dokumen.download', $dokuman) }}" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i>
                            Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection