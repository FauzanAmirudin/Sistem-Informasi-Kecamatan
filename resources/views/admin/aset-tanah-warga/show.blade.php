@extends('layouts.admin')

@section('page-title', 'Detail Aset Tanah Warga')
@section('page-subtitle', 'Detail data aset tanah warga')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.aset-tanah-warga.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin.aset-tanah-warga.edit', $asetTanahWarga) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
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
                <p>Apakah Anda yakin ingin menghapus data aset tanah warga ini?</p>
                <ul>
                    <li><strong>Nama Pemilik:</strong> {{ $asetTanahWarga->nama_pemilik }}</li>
                    <li><strong>NIK:</strong> {{ $asetTanahWarga->nik_pemilik }}</li>
                    <li><strong>Nomor SPH:</strong> {{ $asetTanahWarga->nomor_sph }}</li>
                </ul>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.aset-tanah-warga.destroy', $asetTanahWarga) }}" method="POST">
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
                    Informasi Aset Tanah Warga
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Nama Pemilik</h6>
                        <p>{{ $asetTanahWarga->nama_pemilik }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">NIK Pemilik</h6>
                        <p>{{ $asetTanahWarga->nik_pemilik }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Desa</h6>
                        <p>{{ $asetTanahWarga->desa->nama_desa }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Nomor SPH</h6>
                        <p>{{ $asetTanahWarga->nomor_sph }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Jenis Tanah</h6>
                        <p>
                            @php
                                $jenisTanah = match($asetTanahWarga->jenis_tanah) {
                                    'tanah_kering' => 'Tanah Kering',
                                    'tanah_sawah' => 'Tanah Sawah',
                                    'tanah_pekarangan' => 'Tanah Pekarangan',
                                    'tanah_perkebunan' => 'Tanah Perkebunan',
                                    default => ucfirst(str_replace('_', ' ', $asetTanahWarga->jenis_tanah))
                                };
                            @endphp
                            {{ $jenisTanah }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Status Kepemilikan</h6>
                        <p>
                            @php
                                $statusClass = match($asetTanahWarga->status_kepemilikan) {
                                    'milik_sendiri' => 'success',
                                    'warisan' => 'primary',
                                    'hibah' => 'info',
                                    'jual_beli' => 'warning',
                                    default => 'secondary'
                                };
                                $statusText = match($asetTanahWarga->status_kepemilikan) {
                                    'milik_sendiri' => 'Milik Sendiri',
                                    'warisan' => 'Warisan',
                                    'hibah' => 'Hibah',
                                    'jual_beli' => 'Jual Beli',
                                    default => ucfirst($asetTanahWarga->status_kepemilikan)
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Luas Tanah</h6>
                        <p>{{ number_format($asetTanahWarga->luas_tanah, 2) }} m²</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Nilai Per Meter</h6>
                        <p>Rp {{ number_format($asetTanahWarga->nilai_per_meter, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Nilai Tanah</h6>
                        <p class="fw-bold text-primary">Rp {{ number_format($asetTanahWarga->nilai_tanah, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Tanggal Perolehan</h6>
                        <p>{{ $asetTanahWarga->tanggal_perolehan->format('d F Y') }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="fw-bold">Lokasi/Alamat</h6>
                        <p>{{ $asetTanahWarga->lokasi }}</p>
                    </div>
                </div>
                
                @if($asetTanahWarga->keterangan)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="fw-bold">Keterangan</h6>
                        <p>{{ $asetTanahWarga->keterangan }}</p>
                    </div>
                </div>
                @endif
                
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="fw-bold">Terakhir Diperbarui</h6>
                        <p>{{ $asetTanahWarga->updated_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bukti Kepemilikan -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Bukti Kepemilikan
                </h5>
            </div>
            <div class="card-body text-center">
                @if($asetTanahWarga->bukti_kepemilikan)
                    @php
                        $extension = pathinfo($asetTanahWarga->bukti_kepemilikan, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                        <img src="{{ $asetTanahWarga->file_url }}" 
                             class="img-fluid mb-3 border" alt="Bukti Kepemilikan">
                    @else
                        <div class="p-4 mb-3 bg-light rounded">
                            <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                            <p>Dokumen PDF</p>
                        </div>
                    @endif
                    
                    <a href="{{ $asetTanahWarga->file_url }}" 
                       class="btn btn-primary w-100" target="_blank">
                        <i class="fas fa-download me-1"></i>
                        Download Bukti Kepemilikan
                    </a>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-file-alt fa-4x mb-3"></i>
                        <p>Tidak ada bukti kepemilikan yang tersedia</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Informasi Tambahan -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>ID Aset</span>
                    <span class="badge bg-secondary">{{ $asetTanahWarga->id }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Tanggal Dibuat</span>
                    <span>{{ $asetTanahWarga->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Status</span>
                    <span class="badge bg-success">Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection