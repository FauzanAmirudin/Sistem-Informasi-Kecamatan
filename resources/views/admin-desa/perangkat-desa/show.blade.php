@extends('layouts.admin-desa')

@section('page-title', $perangkatDesa->nama_lengkap)
@section('page-subtitle', 'Detail data perangkat desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.perangkat-desa.edit', $perangkatDesa) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin-desa.perangkat-desa.riwayat', $perangkatDesa) }}" class="btn btn-secondary">
        <i class="fas fa-history me-1"></i>
        Riwayat
    </a>
    <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <!-- Kolom Utama -->
    <div class="col-lg-8">
        <!-- Data Identitas -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-2"></i>
                    Data Identitas Perangkat Desa
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th width="30%">Nama Lengkap</th>
                            <td width="70%">{{ $perangkatDesa->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>{{ $perangkatDesa->jabatan }}</td>
                        </tr>
                        <tr>
                            <th>NIK</th>
                            <td>{{ $perangkatDesa->nik }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $perangkatDesa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        </tr>
                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>{{ $perangkatDesa->tempat_lahir }}, {{ $perangkatDesa->tanggal_lahir->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Usia</th>
                            <td>{{ $perangkatDesa->tanggal_lahir->age }} tahun</td>
                        </tr>
                        <tr>
                            <th>Pendidikan Terakhir</th>
                            <td>{{ $perangkatDesa->pendidikan_terakhir }}</td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td>{{ $perangkatDesa->no_telepon ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Desa Penugasan</th>
                            <td>{{ $perangkatDesa->desa->nama_desa }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($perangkatDesa->status == 'aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>SK Pengangkatan</th>
                            <td>
                                @if($perangkatDesa->sk_pengangkatan)
                                    <a href="{{ $perangkatDesa->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-muted">Belum ada dokumen</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alamat Tempat Tinggal -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
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
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Masa Tugas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Tanggal Mulai Tugas:</strong></p>
                        <p>{{ $perangkatDesa->tanggal_mulai_tugas->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Tanggal Akhir Tugas:</strong></p>
                        <p>
                            @if($perangkatDesa->tanggal_akhir_tugas)
                                {{ $perangkatDesa->tanggal_akhir_tugas->translatedFormat('d F Y') }}
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p class="mb-1"><strong>Lama Tugas:</strong></p>
                        @php
                            $startDate = $perangkatDesa->tanggal_mulai_tugas;
                            $endDate = $perangkatDesa->tanggal_akhir_tugas ?? now();
                            $diff = $startDate->diff($endDate);
                            
                            $years = $diff->y;
                            $months = $diff->m;
                            $days = $diff->d;
                            
                            $duration = '';
                            if ($years > 0) {
                                $duration .= $years . ' tahun ';
                            }
                            if ($months > 0) {
                                $duration .= $months . ' bulan ';
                            }
                            if ($days > 0) {
                                $duration .= $days . ' hari';
                            }
                            
                            if (empty($duration)) {
                                $duration = '0 hari';
                            }
                        @endphp
                        <p>{{ $duration }}</p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p class="mb-1"><strong>Status:</strong></p>
                        <p>
                            @if($perangkatDesa->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
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
                    Job Description
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $perangkatDesa->jobdesk }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Informasi Tambahan -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <!-- Usia -->
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-circle p-2">
                            <i class="fas fa-birthday-cake text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Usia</h6>
                        <p class="mb-0">{{ $perangkatDesa->tanggal_lahir->age }} tahun</p>
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-circle p-2">
                            @if($perangkatDesa->jenis_kelamin == 'L')
                                <i class="fas fa-mars text-primary fa-lg"></i>
                            @else
                                <i class="fas fa-venus text-danger fa-lg"></i>
                            @endif
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Jenis Kelamin</h6>
                        <p class="mb-0">{{ $perangkatDesa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                </div>

                <!-- Masa Kerja -->
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-circle p-2">
                            <i class="fas fa-business-time text-success fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Masa Kerja</h6>
                        <div class="progress mt-1" style="height: 8px;">
                            @php
                                $startDate = $perangkatDesa->tanggal_mulai_tugas;
                                $endDate = $perangkatDesa->tanggal_akhir_tugas;
                                $today = now();
                                
                                if ($endDate) {
                                    $totalDays = $startDate->diffInDays($endDate);
                                    $passedDays = $startDate->diffInDays(min($today, $endDate));
                                    $percentage = ($totalDays > 0) ? min(100, ($passedDays / $totalDays) * 100) : 0;
                                } else {
                                    $percentage = 100;
                                }
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">Mulai: {{ $perangkatDesa->tanggal_mulai_tugas->translatedFormat('d M Y') }}</small>
                    </div>
                </div>

                <!-- Status Dokumen -->
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-circle p-2">
                            <i class="fas fa-file-alt text-info fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Status Dokumen</h6>
                        <p class="mb-0">
                            @if($perangkatDesa->sk_pengangkatan)
                                <span class="badge bg-success">Lengkap</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lengkap</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Data -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-purple text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Data
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Data Dibuat</h6>
                            <small class="text-muted">{{ $perangkatDesa->created_at->translatedFormat('d F Y, H:i') }}</small>
                            <p class="mb-0">Oleh: {{ $perangkatDesa->createdBy->name ?? 'System' }}</p>
                        </div>
                    </div>
                    
                    @if($perangkatDesa->created_at->format('Y-m-d H:i:s') != $perangkatDesa->updated_at->format('Y-m-d H:i:s'))
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="mb-0">Terakhir Diperbarui</h6>
                            <small class="text-muted">{{ $perangkatDesa->updated_at->translatedFormat('d F Y, H:i') }}</small>
                            <p class="mb-0">Oleh: {{ $perangkatDesa->updatedBy->name ?? 'System' }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($perangkatDesa->riwayat->count() > 0)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <a href="{{ route('admin-desa.perangkat-desa.riwayat', $perangkatDesa) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-history me-1"></i>
                                Lihat Semua Riwayat ({{ $perangkatDesa->riwayat->count() }})
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin-desa.perangkat-desa.edit', $perangkatDesa) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>
                        Edit Data
                    </a>
                    <a href="{{ route('admin-desa.desa.show', $perangkatDesa->desa) }}" class="btn btn-info">
                        <i class="fas fa-building me-1"></i>
                        Lihat Data Desa
                    </a>
                    <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-primary">
                        <i class="fas fa-users me-1"></i>
                        Perangkat Desa
                    </a>
                    <a href="{{ route('admin-desa.perangkat-desa.riwayat', $perangkatDesa) }}" class="btn btn-secondary">
                        <i class="fas fa-history me-1"></i>
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
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e0e0e0;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: #6c757d;
        border: 3px solid #fff;
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
    
    .bg-purple {
        background-color: #6f42c1;
    }
</style>
@endpush