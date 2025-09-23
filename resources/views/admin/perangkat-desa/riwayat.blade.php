@extends('layouts.admin')

@section('page-title', 'Riwayat Perangkat: ' . $perangkat->nama_lengkap)
@section('page-subtitle', 'Riwayat perubahan perangkat desa')

@section('page-actions')
<div class="btn-group d-flex flex-wrap mb-3" role="group">
    <a href="{{ route('admin.perangkat-desa.show', $perangkat) }}" class="btn btn-primary py-2 px-3">
        <i class="fas fa-eye me-1"></i>
        <span class="d-none d-md-inline">Lihat Detail</span>
        <span class="d-inline d-md-none">Detail</span>
    </a>
    <a href="{{ route('admin.perangkat-desa.edit', $perangkat) }}" class="btn btn-warning py-2 px-3">
        <i class="fas fa-edit me-1"></i>
        <span class="d-none d-md-inline">Edit</span>
    </a>
    <a href="{{ route('admin.perangkat-desa.index') }}" class="btn btn-secondary py-2 px-3">
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
    }
    
    .timeline-card .card-header {
        border-radius: 0.5rem 0.5rem 0 0;
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
        
        .timeline-item:nth-child(even) .timeline-badge {
            left: -20px;
            right: auto;
        }
    }
    
    /* Card styling */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    /* Badge styling */
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
    
    /* Button styling */
    .btn-outline-info {
        border-width: 2px;
    }
    
    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }
    
    /* Pagination styling */
    .pagination-container {
        margin: 0 auto;
    }
    
    .pagination-container .pagination {
        margin-bottom: 0;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .pagination-container .page-item .page-link {
        border: none;
        padding: 0.75rem 1rem;
        color: #6c757d;
        transition: all 0.2s ease;
    }
    
    .pagination-container .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
        font-weight: 500;
    }
    
    .pagination-container .page-item .page-link:hover {
        background-color: #e9ecef;
        color: #0d6efd;
    }
</style>
@endpush

@section('admin-content')
<div class="row">
    <div class="col-lg-12">
        <!-- Info Perangkat -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    Informasi Perangkat Desa
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Nama Lengkap:</strong></p>
                        <p>{{ $perangkat->nama_lengkap }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Jabatan:</strong></p>
                        <p><span class="badge bg-primary">{{ $perangkat->jabatan }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>NIK:</strong></p>
                        <p>{{ $perangkat->nik }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Desa:</strong></p>
                        <p>{{ $perangkat->desa->nama_desa }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Status:</strong></p>
                        <p>
                            <span class="badge bg-{{ $perangkat->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($perangkat->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Masa Tugas:</strong></p>
                        <p>
                            {{ $perangkat->tanggal_mulai_tugas->format('d/m/Y') }} - 
                            {{ $perangkat->tanggal_akhir_tugas ? $perangkat->tanggal_akhir_tugas->format('d/m/Y') : 'Belum ditentukan' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

<!-- Riwayat Perubahan -->
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2"></i>
            Riwayat Perubahan Data ({{ $riwayat->total() }} record)
        </h5>
    </div>
    <div class="card-body p-0">
        @if($riwayat->count() > 0)
            <div class="timeline-container mt-4">
                @foreach($riwayat as $index => $history)
                <div class="timeline-item mb-4">
                    <div class="timeline-badge bg-{{ $badgeClass = match($history->action_type) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'secondary'
                    } }}">
                        <i class="fas fa-{{ $history->action_type == 'created' ? 'plus' : ($history->action_type == 'updated' ? 'edit' : 'trash') }}"></i>
                    </div>
                    <div class="timeline-card card shadow-sm">
                        <div class="card-header bg-light p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    @php
                                        $actionText = match($history->action_type) {
                                            'created' => 'Dibuat',
                                            'updated' => 'Diperbarui',
                                            'deleted' => 'Dihapus',
                                            default => ucfirst($history->action_type)
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }} me-2">
                                        <i class="fas fa-{{ $history->action_type == 'created' ? 'plus' : ($history->action_type == 'updated' ? 'edit' : 'trash') }} me-1"></i>
                                        {{ $actionText }}
                                    </span>
                                    <span class="text-muted">{{ $history->created_at->format('d/m/Y H:i:s') }}</span>
                                </h6>
                                <span class="badge bg-secondary">
                                    @if($history->changedBy)
                                        Oleh: {{ $history->changedBy->name }}
                                    @else
                                        Oleh: System
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h6 class="text-muted mb-2">Alasan Perubahan:</h6>
                                    <p>{{ $history->change_reason ?: 'Tidak ada alasan' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Data Pada Waktu Itu:</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Nama:</strong></td>
                                            <td>{{ $history->nama_lengkap }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jabatan:</strong></td>
                                            <td>{{ $history->jabatan }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>NIK:</strong></td>
                                            <td>{{ $history->nik }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $history->status == 'aktif' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($history->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Informasi Lainnya:</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Mulai Tugas:</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($history->tanggal_mulai_tugas)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Akhir Tugas:</strong></td>
                                            <td>
                                                @if($history->tanggal_akhir_tugas)
                                                    {{ \Carbon\Carbon::parse($history->tanggal_akhir_tugas)->format('d/m/Y') }}
                                                @else
                                                    Masih Aktif
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Desa:</strong></td>
                                            <td>{{ $history->desa->nama_desa ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal{{ $history->id }}">
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
                                                <td><strong>Jabatan:</strong></td>
                                                <td>{{ $history->jabatan }}</td>
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
                                                <td><strong>Pendidikan:</strong></td>
                                                <td>{{ $history->pendidikan_terakhir }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold">Informasi Lainnya:</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td><strong>No. Telepon:</strong></td>
                                                <td>{{ $history->no_telepon ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Alamat:</strong></td>
                                                <td>{{ $history->alamat }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Mulai Tugas:</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($history->tanggal_mulai_tugas)->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Akhir Tugas:</strong></td>
                                                <td>
                                                    @if($history->tanggal_akhir_tugas)
                                                        {{ \Carbon\Carbon::parse($history->tanggal_akhir_tugas)->format('d/m/Y') }}
                                                    @else
                                                        Masih Aktif
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    <span class="badge bg-{{ $history->status == 'aktif' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($history->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>SK Pengangkatan:</strong></td>
                                                <td>{{ $history->sk_pengangkatan ? 'Ada' : 'Tidak Ada' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                @if($history->jobdesk)
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold">Job Description:</h6>
                                        <p class="text-muted">{{ $history->jobdesk }}</p>
                                    </div>
                                </div>
                                @endif

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold">Informasi Perubahan:</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Jenis Aksi:</strong></td>
                                                <td>
                                                    <span class="badge bg-{{ $badgeClass }}">{{ $actionText }}</span>
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
@endsection