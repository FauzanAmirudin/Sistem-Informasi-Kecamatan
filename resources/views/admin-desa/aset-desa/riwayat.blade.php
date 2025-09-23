@extends('layouts.admin-desa')

@section('page-title', 'Riwayat Aset Desa')
@section('page-subtitle', 'Riwayat perubahan aset desa')

@section('page-actions')
<div class="btn-group d-flex flex-wrap mb-3" role="group">
    <a href="{{ route('admin-desa.aset-desa.show', $aset) }}" class="btn btn-info py-2 px-3">
        <i class="fas fa-eye me-1"></i>
        <span class="d-none d-md-inline">Detail Aset</span>
        <span class="d-inline d-md-none">Detail</span>
    </a>
    <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-secondary py-2 px-3">
        <i class="fas fa-arrow-left me-1"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
</div>
@endsection

@section('admin-content')
<div class="row mb-4">
    <div class="col-lg-12">
        <!-- Informasi Aset -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Aset
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <table class="table table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th style="width: 30%" class="col-4 col-md-3 p-3">Nama Aset</th>
                            <td class="col-8 col-md-9 p-3">{{ $aset->nama_aset }}</td>
                        </tr>
                        <tr>
                            <th class="col-4 col-md-3 p-3">Desa</th>
                            <td class="col-8 col-md-9 p-3">{{ $aset->desa->nama_desa }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Kategori</th>
                            <td class="p-3">
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
                            </td>
                        </tr>
                        <tr>
                            <th class="p-3">Kondisi</th>
                            <td class="p-3">
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
                            </td>
                        </tr>
                        <tr>
                            <th class="p-3">Nilai Sekarang</th>
                            <td class="p-3">Rp {{ number_format($aset->nilai_sekarang, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Lokasi</th>
                            <td class="p-3">{{ $aset->lokasi ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <!-- Riwayat Perubahan -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Perubahan
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                @if($riwayat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%" class="d-none d-md-table-cell">Tanggal</th>
                                    <th width="15%">Jenis Aksi</th>
                                    <th width="20%" class="d-none d-md-table-cell">Diubah Oleh</th>
                                    <th width="30%" class="d-none d-md-table-cell">Alasan Perubahan</th>
                                    <th width="15%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="d-none d-md-table-cell">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $actionClass = match($item->action_type) {
                                                'created' => 'success',
                                                'updated' => 'warning',
                                                'deleted' => 'danger',
                                                'create' => 'success',
                                                'update' => 'warning',
                                                'delete' => 'danger',
                                                default => 'secondary'
                                            };
                                            $actionText = match($item->action_type) {
                                                'created' => 'Dibuat',
                                                'updated' => 'Diperbarui',
                                                'deleted' => 'Dihapus',
                                                'create' => 'Dibuat',
                                                'update' => 'Diperbarui',
                                                'delete' => 'Dihapus',
                                                default => ucfirst($item->action_type)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $actionClass }}">{{ $actionText }}</span>
                                        <div class="d-block d-md-none mt-1">
                                            <small>{{ $item->created_at->format('d/m/Y H:i') }}</small><br>
                                            <small>{{ $item->changedBy->name ?? 'Sistem' }}</small>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $item->changedBy->name ?? 'Sistem' }}</td>
                                    <td class="d-none d-md-table-cell">{{ $item->change_reason ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary py-2 px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                            <i class="fas fa-eye me-1"></i> <span class="d-none d-md-inline">Detail</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5 px-3">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada riwayat perubahan untuk aset ini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Detail Riwayat -->
@foreach($riwayat as $index => $item)
<div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white p-3">
                <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">
                    <i class="fas fa-info-circle me-2"></i>
                    Detail Riwayat - {{ $item->created_at->format('d F Y, H:i') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <!-- Data Aset -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light p-3">
                        <h6 class="card-title mb-0">Data Aset</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th width="30%" class="p-2 p-md-3">Nama Aset</th>
                                    <td class="p-2 p-md-3">{{ $item->nama_aset }}</td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Desa</th>
                                    <td class="p-2 p-md-3">{{ $item->desa->nama_desa ?? 'Tidak diketahui' }}</td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Kategori</th>
                                    <td class="p-2 p-md-3">
                                        @php
                                            $kategoriClass = match($item->kategori_aset) {
                                                'tanah' => 'success',
                                                'bangunan' => 'primary',
                                                'inventaris' => 'warning',
                                                default => 'secondary'
                                            };
                                            $kategoriText = match($item->kategori_aset) {
                                                'tanah' => 'Tanah',
                                                'bangunan' => 'Bangunan',
                                                'inventaris' => 'Inventaris',
                                                default => ucfirst($item->kategori_aset)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $kategoriClass }}">{{ $kategoriText }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Kondisi</th>
                                    <td class="p-2 p-md-3">
                                        @php
                                            $kondisiClass = match($item->kondisi) {
                                                'baik' => 'success',
                                                'rusak_ringan' => 'warning',
                                                'rusak_berat' => 'danger',
                                                default => 'secondary'
                                            };
                                            $kondisiText = match($item->kondisi) {
                                                'baik' => 'Baik',
                                                'rusak_ringan' => 'Rusak Ringan',
                                                'rusak_berat' => 'Rusak Berat',
                                                default => ucfirst($item->kondisi)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $kondisiClass }}">{{ $kondisiText }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Nilai Perolehan</th>
                                    <td class="p-2 p-md-3">Rp {{ number_format($item->nilai_perolehan, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Nilai Sekarang</th>
                                    <td class="p-2 p-md-3">Rp {{ number_format($item->nilai_sekarang, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Lokasi</th>
                                    <td class="p-2 p-md-3">{{ $item->lokasi ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Informasi Lainnya -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light p-3">
                        <h6 class="card-title mb-0">Informasi Lainnya</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th width="30%" class="p-2 p-md-3">Waktu</th>
                                    <td class="p-2 p-md-3">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Jenis Aksi</th>
                                    <td class="p-2 p-md-3">
                                        @php
                                            $actionClass = match($item->action_type) {
                                                'created' => 'success',
                                                'updated' => 'warning',
                                                'deleted' => 'danger',
                                                'create' => 'success',
                                                'update' => 'warning',
                                                'delete' => 'danger',
                                                default => 'secondary'
                                            };
                                            $actionText = match($item->action_type) {
                                                'created' => 'Dibuat',
                                                'updated' => 'Diperbarui',
                                                'deleted' => 'Dihapus',
                                                'create' => 'Dibuat',
                                                'update' => 'Diperbarui',
                                                'delete' => 'Dihapus',
                                                default => ucfirst($item->action_type)
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $actionClass }}">{{ $actionText }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Dilakukan Oleh</th>
                                    <td class="p-2 p-md-3">{{ $item->changedBy->name ?? 'Sistem' }}</td>
                                </tr>
                                <tr>
                                    <th class="p-2 p-md-3">Alasan Perubahan</th>
                                    <td class="p-2 p-md-3">{{ $item->change_reason ?? '-' }}</td>
                                </tr>
                                @if($item->keterangan)
                                <tr>
                                    <th class="p-2 p-md-3">Keterangan</th>
                                    <td class="p-2 p-md-3">{{ $item->keterangan }}</td>
                                </tr>
                                @endif
                                @if($item->bukti_kepemilikan)
                                <tr>
                                    <th class="p-2 p-md-3">Bukti Kepemilikan</th>
                                    <td class="p-2 p-md-3">
                                        <a href="{{ asset('uploads/bukti-aset/' . $item->bukti_kepemilikan) }}" target="_blank" class="btn btn-sm btn-info py-2 px-3">
                                            <i class="fas fa-file-alt me-1"></i> Lihat Dokumen
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3">
                <button type="button" class="btn btn-secondary py-2 px-3 w-100 w-md-auto" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection