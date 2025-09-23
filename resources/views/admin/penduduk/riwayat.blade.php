@extends('layouts.admin')

@section('page-title', 'Riwayat Penduduk: ' . $penduduk->nama_lengkap)
@section('page-subtitle', 'Riwayat perubahan data penduduk')

@section('page-actions')
<div class="btn-group d-flex flex-wrap mb-3" role="group">
    <a href="{{ route('admin.penduduk.show', $penduduk) }}" class="btn btn-primary py-2 px-3">
        <i class="fas fa-eye me-1"></i>
        <span class="d-none d-md-inline">Lihat Detail</span>
        <span class="d-inline d-md-none">Detail</span>
    </a>
    <a href="{{ route('admin.penduduk.edit', $penduduk) }}" class="btn btn-warning py-2 px-3">
        <i class="fas fa-edit me-1"></i>
        <span class="d-none d-md-inline">Edit</span>
    </a>
    <a href="{{ route('admin.penduduk.index') }}" class="btn btn-secondary py-2 px-3">
        <i class="fas fa-arrow-left me-1"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
</div>
@endsection

@push('styles')
<style>
    /* Timeline Styling */
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 4px;
        background: #e9ecef;
        border-radius: 4px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 45px;
    }
    
    .timeline-badge {
        position: absolute;
        left: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        color: white;
        z-index: 1;
    }
    
    .timeline-badge i {
        font-size: 1rem;
    }
    
    .timeline-card {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .timeline-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .timeline-card .card-header {
        border-radius: 0.5rem 0.5rem 0 0;
        padding: 0.75rem 1.25rem;
    }
    
    /* Card styling */
    .info-card {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .info-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .info-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        padding: 0.75rem 1.25rem;
    }
    
    /* Badge styling */
    .badge-outline-success {
        color: #28a745;
        border: 1px solid #28a745;
        background-color: transparent;
    }
    
    .badge-outline-primary {
        color: #007bff;
        border: 1px solid #007bff;
        background-color: transparent;
    }
    
    .badge-outline-danger {
        color: #dc3545;
        border: 1px solid #dc3545;
        background-color: transparent;
    }
    
    /* Button styling */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    /* Responsive adjustments */
    @media (min-width: 768px) {
        .timeline-container::before {
            left: 50%;
            margin-left: -2px;
        }
        
        .timeline-item {
            padding-left: 0;
            width: 50%;
        }
        
        .timeline-item:nth-child(odd) {
            padding-right: 30px;
            padding-left: 0;
            margin-left: 0;
            margin-right: auto;
        }
        
        .timeline-item:nth-child(even) {
            padding-left: 30px;
            padding-right: 0;
            margin-right: 0;
            margin-left: auto;
        }
        
        .timeline-badge {
            left: auto;
            right: -20px;
        }
        
        .timeline-item:nth-child(odd) .timeline-badge {
            right: -20px;
            left: auto;
        }
        
        .timeline-item:nth-child(even) .timeline-badge {
            left: -20px;
            right: auto;
        }
    }
    
    @media (max-width: 767.98px) {
        .timeline-container::before {
            left: 20px;
        }
        
        .timeline-badge {
            left: 0;
        }
    }
    
    /* Pagination styling */
    .pagination-container {
        margin: 1rem 0;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
        padding: 0.5rem;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .page-item:first-child .page-link {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .page-item:last-child .page-link {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .page-link {
        color: #007bff;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid px-0">
    <!-- Informasi Penduduk -->
    <div class="card info-card mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-user me-2 text-primary"></i>
                Informasi Penduduk
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-card fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">NIK</h6>
                            <p class="mb-0 text-muted">{{ $penduduk->nik }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Desa</h6>
                            <p class="mb-0 text-muted">{{ $penduduk->desa->nama_desa }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-venus-mars fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Jenis Kelamin</h6>
                            <p class="mb-0 text-muted">{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-badge fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Status KTP</h6>
                            <p class="mb-0">
                                @if($penduduk->memiliki_ktp)
                                    <span class="badge bg-success">Memiliki KTP</span>
                                @else
                                    <span class="badge bg-warning">Belum Memiliki KTP</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Perubahan Data -->
    <div class="card info-card">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2 text-primary"></i>
                Riwayat Perubahan Data
            </h5>
        </div>
        <div class="card-body p-0">
            @if($riwayat->count() > 0)
                <div class="timeline-container p-3 p-md-4">
                    @foreach($riwayat as $history)
                    <div class="timeline-item">
                        <div class="timeline-badge bg-{{ $history->action_type == 'created' ? 'success' : ($history->action_type == 'updated' ? 'primary' : 'danger') }}">
                            <i class="fas {{ $history->action_type == 'created' ? 'fa-plus' : ($history->action_type == 'updated' ? 'fa-edit' : 'fa-trash') }}"></i>
                        </div>
                        <div class="timeline-card card">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $history->created_at->format('d F Y, H:i') }}
                                </span>
                                <span class="badge bg-{{ $history->action_type == 'created' ? 'success' : ($history->action_type == 'updated' ? 'primary' : 'danger') }}">
                                    {{ $history->action_type == 'created' ? 'Dibuat' : ($history->action_type == 'updated' ? 'Diperbarui' : 'Dihapus') }}
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Data Pada Waktu Itu:</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Nama:</strong></td>
                                                <td>{{ $history->nama_lengkap }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>NIK:</strong></td>
                                                <td>{{ $history->nik }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Kelamin:</strong></td>
                                                <td>{{ $history->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tempat Lahir:</strong></td>
                                                <td>{{ $history->tempat_lahir }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Lahir:</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($history->tanggal_lahir)->format('d/m/Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Informasi Lainnya:</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Agama:</strong></td>
                                                <td>{{ $history->agama }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>{{ $history->status_perkawinan }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pekerjaan:</strong></td>
                                                <td>{{ $history->pekerjaan }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pendidikan:</strong></td>
                                                <td>{{ $history->pendidikan_terakhir }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>RT/RW:</strong></td>
                                                <td>{{ $history->rt }}/{{ $history->rw }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $history->changedBy ? $history->changedBy->name : 'System' }}
                                        </span>
                                        @if($history->change_reason)
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-comment me-1"></i>
                                            {{ $history->change_reason }}
                                        </span>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $history->id }}">
                                        <i class="fas fa-eye me-1"></i>
                                        Lihat Detail Lengkap
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $history->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        Detail Perubahan - {{ $history->created_at->format('d/m/Y H:i:s') }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Data Pada Waktu Itu:</h6>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td><strong>Nama:</strong></td>
                                                    <td>{{ $history->nama_lengkap }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>NIK:</strong></td>
                                                    <td>{{ $history->nik }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jenis Kelamin:</strong></td>
                                                    <td>{{ $history->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tempat Lahir:</strong></td>
                                                    <td>{{ $history->tempat_lahir }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Lahir:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($history->tanggal_lahir)->format('d/m/Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Agama:</strong></td>
                                                    <td>{{ $history->agama }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>{{ $history->status_perkawinan }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Pekerjaan:</strong></td>
                                                    <td>{{ $history->pekerjaan }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Pendidikan:</strong></td>
                                                    <td>{{ $history->pendidikan_terakhir }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alamat:</strong></td>
                                                    <td>{{ $history->alamat }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>RT/RW:</strong></td>
                                                    <td>{{ $history->rt }}/{{ $history->rw }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status KTP:</strong></td>
                                                    <td>{{ $history->memiliki_ktp ? 'Memiliki KTP' : 'Belum Memiliki KTP' }}</td>
                                                </tr>
                                                @if($history->memiliki_ktp && $history->tanggal_rekam_ktp)
                                                <tr>
                                                    <td><strong>Tanggal Rekam KTP:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($history->tanggal_rekam_ktp)->format('d/m/Y') }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td><strong>Klasifikasi Usia:</strong></td>
                                                    <td>{{ $history->klasifikasi_usia }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Informasi Perubahan:</h6>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td><strong>Jenis Aksi:</strong></td>
                                                    <td>
                                                        <span class="badge bg-{{ $history->action_type == 'created' ? 'success' : ($history->action_type == 'updated' ? 'primary' : 'danger') }}">
                                                            {{ $history->action_type == 'created' ? 'Dibuat' : ($history->action_type == 'updated' ? 'Diperbarui' : 'Dihapus') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Diubah Oleh:</strong></td>
                                                    <td>{{ $history->changedBy ? $history->changedBy->name : 'System' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Alasan:</strong></td>
                                                    <td>{{ $history->change_reason ?: '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Waktu:</strong></td>
                                                    <td>{{ $history->created_at->format('d F Y, H:i:s') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada riwayat perubahan data.</p>
                </div>
            @endif
        </div>
        
        @if($riwayat->hasPages())
        <div class="d-flex justify-content-center py-3 border-top bg-white">
            <nav aria-label="Navigasi halaman" class="pagination-container">
                {{ $riwayat->links('vendor.pagination.custom-bootstrap-5') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection