@extends('layouts.admin')

@section('page-title', 'Riwayat Aset Desa')
@section('page-subtitle', 'Riwayat perubahan aset desa')

@section('page-actions')
<div class="btn-group d-flex flex-wrap mb-3" role="group">
    <a href="{{ route('admin.aset-desa.show', $aset) }}" class="btn btn-primary py-2 px-3">
        <i class="fas fa-eye me-1"></i>
        <span class="d-none d-md-inline">Lihat Detail</span>
        <span class="d-inline d-md-none">Detail</span>
    </a>
    <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-secondary py-2 px-3">
        <i class="fas fa-arrow-left me-1"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
</div>
@endsection

@push('styles')
<style>
    /* Timeline styling */
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    .timeline-container:before {
        content: '';
        position: absolute;
        top: 0;
        left: 50px;
        height: 100%;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    .timeline-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        left: 31px;
        top: 15px;
        transform: translateX(-50%);
        z-index: 10;
        color: white;
    }
    .timeline-badge i {
        font-size: 1.25rem;
    }
    .timeline-card {
        margin-left: 70px;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.2s ease;
    }
    .timeline-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .timeline-card .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
        padding: 0.75rem 1.25rem;
    }
    
    /* Card styling */
    .info-card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden;
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
    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .timeline-container:before {
            left: 30px;
        }
        .timeline-badge {
            left: 11px;
        }
        .timeline-card {
            margin-left: 50px;
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
        justify-content: center;
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
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .page-link {
        color: #0d6efd;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    /* Alert styling */
    .alert-info {
        background-color: #cff4fc;
        border-color: #b6effb;
        color: #055160;
        border-radius: 0.375rem;
    }
</style>
@endpush

@section('admin-content')
<div class="container-fluid px-0">
    <!-- Informasi Aset -->
    <div class="card info-card mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-box me-2 text-primary"></i>
                Informasi Aset
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tag fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Nama Aset</h6>
                            <p class="mb-0 text-muted">{{ $aset->nama_aset }}</p>
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
                            <p class="mb-0 text-muted">{{ $aset->desa->nama_desa }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list-alt fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Kategori</h6>
                            <p class="mb-0">
                                @php
                                    $kategoriClass = match($aset->kategori_aset) {
                                        'tanah' => 'success',
                                        'bangunan' => 'primary',
                                        'inventaris' => 'warning',
                                        default => 'secondary'
                                    };
                                    $kategoriText = match($aset->kategori_aset) {
                                        'tanah' => 'Tanah',
                                        'bangunan' => 'Bangunan',
                                        'inventaris' => 'Inventaris',
                                        default => ucfirst($aset->kategori_aset)
                                    };
                                @endphp
                                <span class="badge bg-{{ $kategoriClass }}">{{ $kategoriText }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Kondisi</h6>
                            <p class="mb-0">
                                @php
                                    $kondisiClass = match($aset->kondisi) {
                                        'baik' => 'success',
                                        'rusak_ringan' => 'warning',
                                        'rusak_berat' => 'danger',
                                        default => 'secondary'
                                    };
                                    $kondisiText = match($aset->kondisi) {
                                        'baik' => 'Baik',
                                        'rusak_ringan' => 'Rusak Ringan',
                                        'rusak_berat' => 'Rusak Berat',
                                        default => ucfirst($aset->kondisi)
                                    };
                                @endphp
                                <span class="badge bg-{{ $kondisiClass }}">{{ $kondisiText }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-money-bill-wave fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Nilai Sekarang</h6>
                            <p class="mb-0 text-muted">Rp {{ number_format($aset->nilai_sekarang, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-alt fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Tanggal Perolehan</h6>
                            <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($aset->tanggal_perolehan)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-pin fa-2x text-muted"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Lokasi</h6>
                            <p class="mb-0 text-muted">{{ $aset->lokasi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Riwayat Perubahan -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2 text-primary"></i>
            Riwayat Perubahan
        </h5>
    </div>
    <div class="card-body">
        @if($riwayat->count() > 0)
            <div class="timeline-container">
                @foreach($riwayat as $index => $item)
                    @php
                        $actionClass = match($item->action) {
                            'created' => 'success',
                            'updated' => 'warning',
                            'deleted' => 'danger',
                            default => 'secondary'
                        };
                        $actionText = match($item->action) {
                            'created' => 'Dibuat',
                            'updated' => 'Diperbarui',
                            'deleted' => 'Dihapus',
                            default => ucfirst($item->action)
                        };
                        $actionIcon = match($item->action) {
                            'created' => 'fa-plus-circle',
                            'updated' => 'fa-edit',
                            'deleted' => 'fa-trash',
                            default => 'fa-circle'
                        };
                    @endphp
                    <div class="timeline-item">
                        <div class="timeline-badge bg-{{ $actionClass }}">
                            <i class="fas {{ $actionIcon }}"></i>
                        </div>
                        <div class="timeline-card card">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-{{ $actionClass }} me-2">{{ $actionText }}</span>
                                    <span class="text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <i class="fas fa-user me-2 text-muted"></i>
                                            <strong>Dilakukan Oleh:</strong> {{ $item->changedBy->name ?? 'Sistem' }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-info-circle me-2 text-muted"></i>
                                            <strong>Alasan:</strong> {{ $item->reason ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        @if($item->keterangan)
                                        <p class="mb-1">
                                            <i class="fas fa-sticky-note me-2 text-muted"></i>
                                            <strong>Keterangan:</strong> {{ $item->keterangan }}
                                        </p>
                                        @endif
                                        @if($item->bukti_kepemilikan)
                                        <p class="mb-1">
                                            <i class="fas fa-file-alt me-2 text-muted"></i>
                                            <strong>Bukti Kepemilikan:</strong> Tersedia
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Modal Detail untuk setiap riwayat -->
            @foreach($riwayat as $index => $item)
                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Detail Riwayat - {{ $actionText }} pada {{ $item->created_at->format('d F Y, H:i') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6>Data pada waktu itu:</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="40%">Nama Aset</th>
                                                <td>{{ $item->nama_aset }}</td>
                                            </tr>
                                            <tr>
                                                <th>Desa</th>
                                                <td>{{ $item->desa->nama_desa ?? 'Tidak diketahui' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kategori</th>
                                                <td>
                                                    @php
                                                        $kategoriText = match($item->kategori_aset) {
                                                            'tanah' => 'Tanah',
                                                            'bangunan' => 'Bangunan',
                                                            'inventaris' => 'Inventaris',
                                                            default => ucfirst($item->kategori_aset ?? 'Tidak diketahui')
                                                        };
                                                    @endphp
                                                    {{ $kategoriText }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nilai Perolehan</th>
                                                <td>Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nilai Sekarang</th>
                                                <td>Rp {{ number_format($item->nilai_sekarang, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Perolehan</th>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kondisi</th>
                                                <td>
                                                    @php
                                                        $kondisiText = match($item->kondisi) {
                                                            'baik' => 'Baik',
                                                            'rusak_ringan' => 'Rusak Ringan',
                                                            'rusak_berat' => 'Rusak Berat',
                                                            default => ucfirst($item->kondisi ?? 'Tidak diketahui')
                                                        };
                                                    @endphp
                                                    {{ $kondisiText }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Lokasi</th>
                                                <td>{{ $item->lokasi }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Informasi Lainnya:</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th width="40%">Waktu</th>
                                                <td>{{ $item->created_at->format('d F Y, H:i:s') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Aksi</th>
                                                <td><span class="badge bg-{{ $actionClass }}">{{ $actionText }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>Dilakukan Oleh</th>
                                                <td>{{ $item->changedBy->name ?? 'Sistem' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alasan Perubahan</th>
                                                <td>{{ $item->reason ?? '-' }}</td>
                                            </tr>
                                        </table>
                                        
                                        @if($item->keterangan)
                                        <div class="mt-3">
                                            <h6>Keterangan:</h6>
                                            <p>{{ $item->keterangan }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($item->bukti_kepemilikan)
                                        <div class="mt-3">
                                            <h6>Bukti Kepemilikan:</h6>
                                            <p><i class="fas fa-file-alt me-1"></i> Tersedia pada waktu itu</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($item->action == 'updated' && $index < count($riwayat) - 1)
                                <div class="mt-3">
                                    <h6>Perubahan:</h6>
                                    @php
                                        $previousItem = $riwayat[$index + 1] ?? null;
                                        $changes = [];
                                        
                                        if ($previousItem) {
                                            if ($item->nama_aset != $previousItem->nama_aset) {
                                                $changes[] = ["field" => "Nama Aset", "from" => $previousItem->nama_aset, "to" => $item->nama_aset];
                                            }
                                            if ($item->desa_id != $previousItem->desa_id) {
                                                $changes[] = ["field" => "Desa", "from" => $previousItem->desa->nama_desa ?? 'Tidak diketahui', "to" => $item->desa->nama_desa ?? 'Tidak diketahui'];
                                            }
                                            if ($item->kategori_aset != $previousItem->kategori_aset) {
                                                $changes[] = ["field" => "Kategori", "from" => ucfirst($previousItem->kategori_aset), "to" => ucfirst($item->kategori_aset)];
                                            }
                                            if ($item->nilai_perolehan != $previousItem->nilai_perolehan) {
                                                $changes[] = ["field" => "Nilai Perolehan", "from" => "Rp " . number_format($previousItem->nilai_perolehan, 0, ',', '.'), "to" => "Rp " . number_format($item->nilai_perolehan, 0, ',', '.')];
                                            }
                                            if ($item->nilai_sekarang != $previousItem->nilai_sekarang) {
                                                $changes[] = ["field" => "Nilai Sekarang", "from" => "Rp " . number_format($previousItem->nilai_sekarang, 0, ',', '.'), "to" => "Rp " . number_format($item->nilai_sekarang, 0, ',', '.')];
                                            }
                                            if ($item->tanggal_perolehan != $previousItem->tanggal_perolehan) {
                                                $changes[] = ["field" => "Tanggal Perolehan", "from" => \Carbon\Carbon::parse($previousItem->tanggal_perolehan)->format('d/m/Y'), "to" => \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y')];
                                            }
                                            if ($item->kondisi != $previousItem->kondisi) {
                                                $kondisiFrom = match($previousItem->kondisi) {
                                                    'baik' => 'Baik',
                                                    'rusak_ringan' => 'Rusak Ringan',
                                                    'rusak_berat' => 'Rusak Berat',
                                                    default => ucfirst($previousItem->kondisi ?? 'Tidak diketahui')
                                                };
                                                $kondisiTo = match($item->kondisi) {
                                                    'baik' => 'Baik',
                                                    'rusak_ringan' => 'Rusak Ringan',
                                                    'rusak_berat' => 'Rusak Berat',
                                                    default => ucfirst($item->kondisi ?? 'Tidak diketahui')
                                                };
                                                $changes[] = ["field" => "Kondisi", "from" => $kondisiFrom, "to" => $kondisiTo];
                                            }
                                            if ($item->lokasi != $previousItem->lokasi) {
                                                $changes[] = ["field" => "Lokasi", "from" => $previousItem->lokasi, "to" => $item->lokasi];
                                            }
                                            if ($item->keterangan != $previousItem->keterangan) {
                                                $changes[] = ["field" => "Keterangan", "from" => $previousItem->keterangan ?: '-', "to" => $item->keterangan ?: '-'];
                                            }
                                            // Bukti kepemilikan berubah
                                            if ($item->bukti_kepemilikan != $previousItem->bukti_kepemilikan) {
                                                $from = $previousItem->bukti_kepemilikan ? 'Ada' : 'Tidak ada';
                                                $to = $item->bukti_kepemilikan ? 'Ada' : 'Tidak ada';
                                                $changes[] = ["field" => "Bukti Kepemilikan", "from" => $from, "to" => $to];
                                            }
                                        }
                                    @endphp
                                    
                                    @if(count($changes) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Nilai Sebelumnya</th>
                                                    <th>Nilai Baru</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($changes as $change)
                                                <tr>
                                                    <td>{{ $change['field'] }}</td>
                                                    <td>{{ $change['from'] }}</td>
                                                    <td>{{ $change['to'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted">Tidak ada perubahan signifikan terdeteksi.</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada riwayat perubahan untuk aset ini.
                </div>
            </div>
        @endif
    </div>
    
    @if($riwayat->hasPages())
    <div class="d-flex justify-content-center py-3 border-top bg-white">
        <nav aria-label="Navigasi halaman">
            {{ $riwayat->links('vendor.pagination.custom-bootstrap-5') }}
        </nav>
    </div>
    @endif
</div>
@endsection