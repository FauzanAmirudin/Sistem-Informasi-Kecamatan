@extends('layouts.admin')

@section('page-title', 'Detail Aset Desa')
@section('page-subtitle', 'Detail data aset desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin.aset-desa.edit', $asetDesa) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin.aset-desa.riwayat', $asetDesa) }}" class="btn btn-info">
        <i class="fas fa-history me-1"></i>
        Riwayat
    </a>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
        <i class="fas fa-trash me-1"></i>
        Hapus
    </button>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus aset <strong>{{ $asetDesa->nama_aset }}</strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan akan menyimpan data ke riwayat.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.aset-desa.destroy', $asetDesa) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('admin-content')
<div class="row">
    <!-- Informasi Utama -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Aset Desa
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Nama Aset</h6>
                        <h5>{{ $asetDesa->nama_aset }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Desa</h6>
                        <h5>{{ $asetDesa->desa->nama_desa }}</h5>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Kategori</h6>
                        @php
                            $kategoriClass = match($asetDesa->kategori_aset) {
                                'tanah' => 'success',
                                'bangunan' => 'primary',
                                'inventaris' => 'warning',
                                default => 'secondary'
                            };
                            $kategoriText = match($asetDesa->kategori_aset) {
                                'tanah' => 'Tanah',
                                'bangunan' => 'Bangunan',
                                'inventaris' => 'Inventaris',
                                default => ucfirst($asetDesa->kategori_aset)
                            };
                        @endphp
                        <h5><span class="badge bg-{{ $kategoriClass }}">{{ $kategoriText }}</span></h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Kondisi</h6>
                        @php
                            $kondisiClass = match($asetDesa->kondisi) {
                                'baik' => 'success',
                                'rusak_ringan' => 'warning',
                                'rusak_berat' => 'danger',
                                default => 'secondary'
                            };
                            $kondisiText = match($asetDesa->kondisi) {
                                'baik' => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat',
                                default => ucfirst($asetDesa->kondisi)
                            };
                        @endphp
                        <h5><span class="badge bg-{{ $kondisiClass }}">{{ $kondisiText }}</span></h5>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Nilai Perolehan</h6>
                        <h5>Rp {{ number_format($asetDesa->nilai_perolehan, 0, ',', '.') }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Nilai Sekarang</h6>
                        <h5>Rp {{ number_format($asetDesa->nilai_sekarang, 0, ',', '.') }}</h5>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Tanggal Perolehan</h6>
                        <h5>{{ $asetDesa->tanggal_perolehan->format('d F Y') }}</h5>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Lokasi</h6>
                        <h5>{{ $asetDesa->lokasi }}</h5>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-1">Keterangan</h6>
                        <p>{{ $asetDesa->keterangan ?: 'Tidak ada keterangan' }}</p>
                    </div>
                </div>
                
                @if($asetDesa->bukti_kepemilikan)
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-2">Bukti Kepemilikan</h6>
                        <a href="{{ asset('storage/' . $asetDesa->bukti_kepemilikan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file-alt me-1"></i>
                            Lihat Dokumen Bukti Kepemilikan
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar Informasi -->
    <div class="col-md-4">
        <!-- Card Informasi Tambahan -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Dibuat Pada</h6>
                    <p>{{ $asetDesa->created_at->format('d F Y, H:i') }}</p>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Terakhir Diperbarui</h6>
                    <p>{{ $asetDesa->updated_at->format('d F Y, H:i') }}</p>
                </div>
                
                @if($asetDesa->updated_by)
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Diperbarui Oleh</h6>
                    <p>{{ $asetDesa->updatedBy->name ?? 'Tidak diketahui' }}</p>
                </div>
                @endif
                
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Status</h6>
                    <span class="badge bg-success">Aktif</span>
                </div>
                
                <div>
                    <h6 class="text-muted mb-1">Jumlah Riwayat Perubahan</h6>
                    <a href="{{ route('admin.aset-desa.riwayat', $asetDesa) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-history me-1"></i>
                        {{ $asetDesa->riwayat->count() }} Riwayat
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Card Informasi Desa -->
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Informasi Desa
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Nama Desa</h6>
                    <h5>{{ $asetDesa->desa->nama_desa }}</h5>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-1">Kode Desa</h6>
                    <p>{{ $asetDesa->desa->kode_desa }}</p>
                </div>
                
                <div>
                    <a href="{{ route('admin.desa.show', $asetDesa->desa) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>
                        Lihat Detail Desa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection