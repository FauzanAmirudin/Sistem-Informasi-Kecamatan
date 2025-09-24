@extends('layouts.admin-desa')

@section('page-title', 'Data Desa')
@section('page-subtitle', 'Informasi dan profil desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.desa.edit') }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit Profil Desa
    </a>
    <a href="{{ route('admin-desa.desa.show') }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
</div>
@endsection

@section('admin-content')
<!-- Informasi Dasar Desa -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-home me-2"></i>
                    Profil Desa {{ $desa->nama_desa }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Nama Desa:</td>
                                <td>{{ $desa->nama_desa }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kode Desa:</td>
                                <td><span class="badge bg-secondary">{{ $desa->kode_desa }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kepala Desa:</td>
                                <td>{{ $desa->kepala_desa }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Alamat:</td>
                                <td>{{ $desa->alamat }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kode Pos:</td>
                                <td>{{ $desa->kode_pos }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Luas Wilayah:</td>
                                <td>{{ $desa->luas_wilayah ? number_format($desa->luas_wilayah, 2) . ' km²' : 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Koordinat:</td>
                                <td>
                                    @if($desa->latitude && $desa->longitude)
                                        {{ $desa->latitude }}, {{ $desa->longitude }}
                                        <br><small class="text-muted">
                                            <a href="https://maps.google.com/?q={{ $desa->latitude }},{{ $desa->longitude }}" 
                                               target="_blank" class="text-decoration-none">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                Lihat di Google Maps
                                            </a>
                                        </small>
                                    @else
                                        <span class="text-muted">Belum diisi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
                                <td>
                                    <span class="badge bg-{{ $desa->status == 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($desa->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Terakhir Update:</td>
                                <td>
                                    @if($desa->last_updated_at)
                                        {{ $desa->last_updated_at->format('d/m/Y H:i') }}
                                        <br><small class="text-muted">({{ $desa->last_updated_at->diffForHumans() }})</small>
                                    @else
                                        <span class="text-danger">Belum pernah update</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card bg-light shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-users text-primary me-2"></i>
                            Total Penduduk
                        </h5>
                        <h2 class="text-primary mb-0">{{ number_format($totalPenduduk) }}</h2>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Laki-laki</small>
                                    <h5>{{ number_format($pendudukPria) }}</h5>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Perempuan</small>
                                    <h5>{{ number_format($pendudukWanita) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card bg-light shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-user-tie text-success me-2"></i>
                            Perangkat Desa Aktif
                        </h5>
                        <h2 class="text-success mb-0">{{ $perangkatAktif }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dokumen Desa -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Dokumen Desa
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">SK Kepala Desa</h6>
                                @if($desa->sk_kepala_desa)
                                    <a href="{{ $desa->sk_kepala_desa_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download me-1"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-muted">Belum diunggah</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Monografi Desa</h6>
                                @if($desa->monografi_file)
                                    <a href="{{ $desa->monografi_file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download me-1"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-muted">Belum diunggah</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Komoditas Unggulan:</h6>
                    <p>{{ $desa->komoditas_unggulan ?: 'Belum diisi' }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Kondisi Sosial Ekonomi:</h6>
                    <p>{{ $desa->kondisi_sosial_ekonomi ?: 'Belum diisi' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection