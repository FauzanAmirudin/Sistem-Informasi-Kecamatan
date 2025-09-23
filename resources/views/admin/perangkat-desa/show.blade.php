@extends('layouts.admin')

@section('page-title', 'Detail Perangkat: ' . $perangkatDesa->nama_lengkap)
@section('page-subtitle', 'Detail data perangkat desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.perangkat-desa.edit', $perangkatDesa) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin.perangkat-desa.riwayat', $perangkatDesa) }}" class="btn btn-info">
        <i class="fas fa-history me-1"></i>
        Riwayat
    </a>
    <a href="{{ route('admin.perangkat-desa.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-user-tie me-2"></i>
                    Data Identitas Perangkat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" width="40%">Nama Lengkap:</td>
                                <td><strong>{{ $perangkatDesa->nama_lengkap }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jabatan:</td>
                                <td><span class="badge bg-primary fs-6">{{ $perangkatDesa->jabatan }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">NIK:</td>
                                <td><code class="fs-6">{{ $perangkatDesa->nik }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jenis Kelamin:</td>
                                <td>
                                    <span class="badge bg-{{ $perangkatDesa->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                                        {{ $perangkatDesa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tempat, Tanggal Lahir:</td>
                                <td>{{ $perangkatDesa->tempat_lahir }}, {{ $perangkatDesa->tanggal_lahir->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Usia:</td>
                                <td>
                                    <span class="badge bg-info">{{ \Carbon\Carbon::parse($perangkatDesa->tanggal_lahir)->age }} tahun</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" width="40%">Pendidikan Terakhir:</td>
                                <td>{{ $perangkatDesa->pendidikan_terakhir }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">No. Telepon:</td>
                                <td>{{ $perangkatDesa->no_telepon ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Desa Penugasan:</td>
                                <td><strong>{{ $perangkatDesa->desa->nama_desa }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
                                <td>
                                    <span class="badge bg-{{ $perangkatDesa->status == 'aktif' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($perangkatDesa->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">SK Pengangkatan:</td>
                                <td>
                                    @if($perangkatDesa->sk_pengangkatan)
                                        <a href="{{ Storage::url($perangkatDesa->sk_pengangkatan) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-download me-1"></i>
                                            Download SK
                                        </a>
                                    @else
                                        <span class="text-muted">Belum diupload</span>
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
                <p class="mb-0">{{ $perangkatDesa->alamat }}</p>
            </div>
        </div>

        <!-- Masa Tugas -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Masa Tugas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Tanggal Mulai Tugas:</h6>
                        <p class="text-muted">{{ $perangkatDesa->tanggal_mulai_tugas->format('d F Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Tanggal Akhir Tugas:</h6>
                        <p class="text-muted">
                            @if($perangkatDesa->tanggal_akhir_tugas)
                                {{ $perangkatDesa->tanggal_akhir_tugas->format('d F Y') }}
                            @else
                                <span class="text-success">Masih Aktif</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-bold">Lama Tugas:</h6>
                        <p class="text-muted">
                            @php
                                $endDate = $perangkatDesa->tanggal_akhir_tugas ?: now();
                                $lamaTugas = $perangkatDesa->tanggal_mulai_tugas->diffForHumans($endDate, true);
                            @endphp
                            {{ $lamaTugas }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Description -->
        @if($perangkatDesa->jobdesk)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Job Description / Tugas Pokok
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $perangkatDesa->jobdesk }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Panel Samping -->
    <div class="col-lg-4">
        <!-- Statistik Personal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-primary mb-1">{{ \Carbon\Carbon::parse($perangkatDesa->tanggal_lahir)->age }}</h4>
                            <small class="text-muted">Tahun</small>
                            <br><span class="badge bg-primary">{{ $perangkatDesa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Masa Kerja:</h6>
                    @php
                        $masaKerja = $perangkatDesa->tanggal_mulai_tugas->diffForHumans(null, true);
                    @endphp
                    <div class="progress mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: 70%">
                            {{ $masaKerja }}
                        </div>
                    </div>
                    <small class="text-muted">Sejak {{ $perangkatDesa->tanggal_mulai_tugas->format('d F Y') }}</small>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Status Dokumen:</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>SK Pengangkatan:</span>
                        @if($perangkatDesa->sk_pengangkatan)
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Ada
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
            <div class="card-header bg-dark text-white">
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
                                {{ $perangkatDesa->created_at->format('d F Y, H:i') }}
                                <br><small class="text-muted">{{ $perangkatDesa->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                    
                    @if($perangkatDesa->updated_at != $perangkatDesa->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Terakhir Diperbarui</h6>
                            <p class="timeline-text">
                                {{ $perangkatDesa->updated_at->format('d F Y, H:i') }}
                                <br><small class="text-muted">{{ $perangkatDesa->updated_at->diffForHumans() }}</small>
                                @if($perangkatDesa->updatedBy)
                                    <br><small class="text-muted">oleh {{ $perangkatDesa->updatedBy->name }}</small>
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('admin.perangkat-desa.riwayat', $perangkatDesa) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-history me-1"></i>
                        Lihat Semua Riwayat ({{ $perangkatDesa->riwayat->count() }})
                    </a>
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
                    <a href="{{ route('admin.perangkat-desa.edit', $perangkatDesa) }}" 
                       class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i>
                        Edit Data Perangkat
                    </a>
                    
                    <a href="{{ route('admin.desa.show', $perangkatDesa->desa) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>
                        Lihat Data Desa
                    </a>
                    
                    <a href="{{ route('admin.perangkat-desa.index', ['desa_id' => $perangkatDesa->desa_id]) }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-users me-2"></i>
                        Perangkat Se-Desa
                    </a>
                    
                    <a href="{{ route('admin.perangkat-desa.riwayat', $perangkatDesa) }}" 
                       class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>
                        Riwayat Perubahan
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