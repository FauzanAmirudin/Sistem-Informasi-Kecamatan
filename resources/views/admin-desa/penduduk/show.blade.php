@extends('layouts.admin-desa')

@section('page-title', 'Detail Penduduk: ' . $penduduk->nama_lengkap)
@section('page-subtitle', 'Detail data penduduk')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.penduduk.edit', $penduduk) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin-desa.penduduk.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <!-- Data Utama -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    Data Identitas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" width="40%">NIK:</td>
                                <td><code class="fs-6">{{ $penduduk->nik }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Lengkap:</td>
                                <td><strong>{{ $penduduk->nama_lengkap }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin:</td>
                                <td>
                                    <span class="badge bg-{{ $penduduk->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                                        {{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat, Tanggal Lahir:</td>
                                <td>{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Usia:</td>
                                <td>
                                    <span class="badge bg-info">{{ $penduduk->usia }} tahun</span>
                                    <small class="text-muted">({{ $penduduk->klasifikasi_usia }})</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Agama:</td>
                                <td>{{ $penduduk->agama }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" width="40%">Status Perkawinan:</td>
                                <td>{{ $penduduk->status_perkawinan }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pekerjaan:</td>
                                <td><strong>{{ $penduduk->pekerjaan }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pendidikan Terakhir:</td>
                                <td>{{ $penduduk->pendidikan_terakhir }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">RT/RW:</td>
                                <td>
                                    <span class="badge bg-secondary">RT {{ $penduduk->rt }}</span>
                                    <span class="badge bg-secondary">RW {{ $penduduk->rw }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Desa:</td>
                                <td><strong>{{ $penduduk->desa->nama_desa }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status KTP:</td>
                                <td>
                                    @if($penduduk->memiliki_ktp)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Sudah Memiliki KTP
                                        </span>
                                        @if($penduduk->tanggal_rekam_ktp)
                                            <br><small class="text-muted">Direkam: {{ $penduduk->tanggal_rekam_ktp->format('d/m/Y') }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>
                                            Belum Memiliki KTP
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Alamat Tempat Tinggal
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Alamat Lengkap:</h6>
                        <p class="text-muted mb-3">{{ $penduduk->alamat }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">RT:</h6>
                        <p class="text-muted">{{ $penduduk->rt }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">RW:</h6>
                        <p class="text-muted">{{ $penduduk->rw }}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="fw-bold">Desa:</h6>
                        <p class="text-muted">{{ $penduduk->desa->nama_desa }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Samping -->
    <div class="col-lg-4">
        <!-- Statistik Personal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-primary mb-1">{{ $penduduk->usia }}</h4>
                            <small class="text-muted">Tahun</small>
                            <br><span class="badge bg-primary">{{ $penduduk->klasifikasi_usia }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Kategori Usia:</h6>
                    <div class="progress mb-2" style="height: 20px;">
                        @php
                            $usiaPersentase = min(($penduduk->usia / 80) * 100, 100);
                            $warna = 'success';
                            if($penduduk->usia < 17) $warna = 'info';
                            elseif($penduduk->usia > 60) $warna = 'warning';
                        @endphp
                        <div class="progress-bar bg-{{ $warna }}" style="width: {{ $usiaPersentase }}%">
                            {{ $penduduk->usia }} tahun
                        </div>
                    </div>
                    <small class="text-muted">Klasifikasi: {{ $penduduk->klasifikasi_usia }}</small>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Status Dokumen:</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>KTP:</span>
                        @if($penduduk->memiliki_ktp)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Lengkap
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times"></i> Belum Ada
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline/History -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Data
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Data Dibuat</h6>
                            <p class="timeline-text">
                                {{ $penduduk->created_at->format('d F Y, H:i') }}
                                <br><small class="text-muted">{{ $penduduk->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                    
                    @if($penduduk->updated_at != $penduduk->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Terakhir Diperbarui</h6>
                            <p class="timeline-text">
                                {{ $penduduk->updated_at->format('d F Y, H:i') }}
                                <br><small class="text-muted">{{ $penduduk->updated_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    @if($penduduk->tanggal_rekam_ktp)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">KTP Direkam</h6>
                            <p class="timeline-text">
                                {{ $penduduk->tanggal_rekam_ktp->format('d F Y') }}
                                <br><small class="text-muted">{{ $penduduk->tanggal_rekam_ktp->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin-desa.penduduk.edit', $penduduk) }}" 
                       class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data Penduduk
                    </a>
                    
                    <a href="{{ route('admin-desa.desa.show') }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>
                        Lihat Data Desa
                    </a>
                    
                    <a href="{{ route('admin-desa.penduduk.index', ['rt' => $penduduk->rt, 'rw' => $penduduk->rw]) }}" 
                       class="btn btn-outline-success">
                        <i class="fas fa-map-signs me-2"></i>
                        Penduduk Se-RT/RW
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 15px;
    height: 100%;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    padding-left: 40px;
}

.timeline-marker {
    position: absolute;
    left: 9px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 13px;
    margin-bottom: 0;
}
</style>
@endpush
