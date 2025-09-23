@extends('layouts.admin-desa')

@section('page-title', 'Riwayat Perubahan: ' . $perangkat->nama_lengkap)
@section('page-subtitle', 'Riwayat perubahan perangkat desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.perangkat-desa.show', $perangkat) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
    <a href="{{ route('admin-desa.perangkat-desa.edit', $perangkat) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-2"></i>
                    Informasi Perangkat Desa
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Nama Lengkap:</strong></p>
                        <p>{{ $perangkat->nama_lengkap }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Jabatan:</strong></p>
                        <p>{{ $perangkat->jabatan }}</p>
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
                            @if($perangkat->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
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

        <div class="card shadow-sm">
            <div class="card-header bg-purple text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Perubahan Data
                </h5>
            </div>
            <div class="card-body">
                @if($riwayat->count() > 0)
                    <div class="timeline-container">
                        @foreach($riwayat as $item)
                            <div class="timeline-item">
                                <div class="timeline-badge {{ $loop->first ? 'bg-primary' : 'bg-secondary' }}">
                                    <i class="fas {{ $loop->first ? 'fa-star' : 'fa-edit' }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                {{ $loop->first ? 'Data Dibuat' : 'Perubahan #' . ($riwayat->count() - $loop->index) }}
                                            </h6>
                                            <span class="badge bg-info">
                                                {{ $item->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <strong>Alasan Perubahan:</strong>
                                                <p>{{ $item->change_reason ?: 'Pembuatan data awal' }}</p>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <strong>Diubah Oleh:</strong>
                                                <p>{{ $item->user->name ?? 'System' }}</p>
                                            </div>
                                            
                                            @if(!$loop->first)
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Field</th>
                                                                <th>Nilai Lama</th>
                                                                <th>Nilai Baru</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $changes = json_decode($item->perubahan, true) ?? [];
                                                $oldData = $changes['old'] ?? [];
                                                $newData = $changes['new'] ?? [];
                                                                
                                                                // Field labels mapping
                                                                $fieldLabels = [
                                                                    'nama_lengkap' => 'Nama Lengkap',
                                                                    'jabatan' => 'Jabatan',
                                                                    'nik' => 'NIK',
                                                                    'jenis_kelamin' => 'Jenis Kelamin',
                                                                    'tempat_lahir' => 'Tempat Lahir',
                                                                    'tanggal_lahir' => 'Tanggal Lahir',
                                                                    'pendidikan_terakhir' => 'Pendidikan Terakhir',
                                                                    'no_telepon' => 'No. Telepon',
                                                                    'alamat' => 'Alamat',
                                                                    'tanggal_mulai_tugas' => 'Tanggal Mulai Tugas',
                                                                    'tanggal_akhir_tugas' => 'Tanggal Akhir Tugas',
                                                                    'jobdesk' => 'Job Description',
                                                                    'sk_pengangkatan' => 'SK Pengangkatan',
                                                                    'status' => 'Status',
                                                                ];
                                                                
                                                                // Format values function
                                                                $formatPerangkatValue = function($field, $value) {
                                                                    if (is_null($value)) return '<span class="text-muted">Kosong</span>';
                                                                    
                                                                    switch ($field) {
                                                                        case 'jenis_kelamin':
                                                                            return $value == 'L' ? 'Laki-laki' : 'Perempuan';
                                                                        case 'tanggal_lahir':
                                                                        case 'tanggal_mulai_tugas':
                                                                        case 'tanggal_akhir_tugas':
                                                                            return $value ? date('d/m/Y', strtotime($value)) : '<span class="text-muted">Kosong</span>';
                                                                        case 'status':
                                                                            if ($value == 'aktif') {
                                                                                return '<span class="badge bg-success">Aktif</span>';
                                                                            } else {
                                                                                return '<span class="badge bg-danger">Tidak Aktif</span>';
                                                                            }
                                                                        case 'sk_pengangkatan':
                                                                            return $value ? '<span class="badge bg-info">File Diupload</span>' : '<span class="text-muted">Tidak Ada</span>';
                                                                        default:
                                                                            return $value;
                                                                    }
                                                                }
                                                            @endphp
                                                            
                                                            @foreach($newData as $field => $value)
                                                                @if(isset($fieldLabels[$field]) && (isset($oldData[$field]) || is_null($oldData[$field])))
                                                                    @if($oldData[$field] != $value || (is_null($oldData[$field]) && !is_null($value)))
                                                                        <tr>
                                                                            <td><strong>{{ $fieldLabels[$field] }}</strong></td>
                                                                            <td>{!! $formatPerangkatValue($field, $oldData[$field] ?? null) !!}</td>
                                                                            <td>{!! $formatPerangkatValue($field, $value) !!}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Ini adalah data awal saat perangkat desa dibuat.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada riwayat perubahan data untuk perangkat desa ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-purple {
        background-color: #6f42c1;
    }
    
    .timeline-container {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline-container:before {
        content: '';
        position: absolute;
        top: 0;
        left: 20px;
        height: 100%;
        width: 4px;
        background: #e9ecef;
        border-radius: 2px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-badge {
        position: absolute;
        top: 0;
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
    
    .timeline-content {
        margin-left: 60px;
        background: #fff;
        border-radius: 0.25rem;
    }
</style>
@endpush