@extends('layouts.admin-desa')

@section('page-title', 'Detail Aset: ' . $asetDesa->nama_aset)
@section('page-subtitle', 'Detail data aset desa')

@section('page-actions')
<div class="btn-group d-flex flex-wrap mb-3" role="group">
    <a href="{{ route('admin-desa.aset-desa.edit', $asetDesa) }}" class="btn btn-warning py-2 px-3">
        <i class="fas fa-edit me-1"></i>
        <span class="d-none d-md-inline">Edit</span>
    </a>
    <a href="{{ route('admin-desa.aset-desa.riwayat', $asetDesa) }}" class="btn btn-info py-2 px-3">
        <i class="fas fa-history me-1"></i>
        <span class="d-none d-md-inline">Riwayat</span>
    </a>
    <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-secondary py-2 px-3">
        <i class="fas fa-arrow-left me-1"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Data Aset Desa
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <table class="table table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th style="width: 30%" class="col-4 col-md-3 p-3">Nama Aset</th>
                            <td class="col-8 col-md-9 p-3">{{ $asetDesa->nama_aset }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Kategori</th>
                            <td class="p-3">
                                @if($asetDesa->kategori_aset == 'tanah')
                                    <span class="badge bg-success">Tanah</span>
                                @elseif($asetDesa->kategori_aset == 'bangunan')
                                    <span class="badge bg-primary">Bangunan</span>
                                @elseif($asetDesa->kategori_aset == 'inventaris')
                                    <span class="badge bg-info">Inventaris</span>
                                @else
                                    <span class="badge bg-secondary">{{ $asetDesa->kategori_aset }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="p-3">Deskripsi</th>
                            <td class="p-3">{{ $asetDesa->deskripsi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Nilai Perolehan</th>
                            <td class="p-3">Rp {{ number_format($asetDesa->nilai_perolehan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Nilai Sekarang</th>
                            <td class="p-3">Rp {{ number_format($asetDesa->nilai_sekarang, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Tanggal Perolehan</th>
                            <td class="p-3">{{ $asetDesa->tanggal_perolehan->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Kondisi</th>
                            <td class="p-3">
                                @if($asetDesa->kondisi == 'baik')
                                    <span class="badge bg-success">Baik</span>
                                @elseif($asetDesa->kondisi == 'rusak_ringan')
                                    <span class="badge bg-warning text-dark">Rusak Ringan</span>
                                @elseif($asetDesa->kondisi == 'rusak_berat')
                                    <span class="badge bg-danger">Rusak Berat</span>
                                @else
                                    <span class="badge bg-secondary">{{ $asetDesa->kondisi }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="p-3">Lokasi</th>
                            <td class="p-3">{{ $asetDesa->lokasi }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Keterangan</th>
                            <td class="p-3">{{ $asetDesa->keterangan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Informasi Tambahan
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <table class="table table-striped table-responsive">
                    <tbody>
                        <tr>
                            <th style="width: 30%" class="col-4 col-md-3 p-3">Desa</th>
                            <td class="col-8 col-md-9 p-3">{{ $asetDesa->desa->nama_desa }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Tanggal Dibuat</th>
                            <td class="p-3">{{ $asetDesa->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th class="p-3">Terakhir Diperbarui</th>
                            <td class="p-3">{{ $asetDesa->updated_at->format('d F Y, H:i') }}</td>
                        </tr>
                        @if($asetDesa->updated_by)
                        <tr>
                            <th class="p-3">Diperbarui Oleh</th>
                            <td class="p-3">{{ $asetDesa->updatedBy->name ?? '-' }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Bukti Kepemilikan -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Bukti Kepemilikan
                </h5>
            </div>
            <div class="card-body text-center p-3 p-md-4">
                @if($asetDesa->bukti_kepemilikan)
                    @php
                        $extension = pathinfo(public_path('uploads/' . $asetDesa->bukti_kepemilikan), PATHINFO_EXTENSION);
                        $isPdf = strtolower($extension) === 'pdf';
                    @endphp
                    
                    @if($isPdf)
                        <div class="mb-3">
                            <i class="fas fa-file-pdf fa-5x text-danger"></i>
                        </div>
                        <h5>Dokumen PDF</h5>
                        <a href="{{ asset('uploads/' . $asetDesa->bukti_kepemilikan) }}" target="_blank" class="btn btn-primary mt-2 py-2 px-3 w-100 w-md-auto">
                            <i class="fas fa-eye me-1"></i> <span class="d-none d-md-inline">Lihat Dokumen</span><span class="d-inline d-md-none">Lihat</span>
                        </a>
                    @else
                        <div class="mb-3">
                            <img src="{{ asset('uploads/' . $asetDesa->bukti_kepemilikan) }}" alt="Bukti Kepemilikan" class="img-fluid rounded" style="max-height: 300px; max-width: 100%;">
                        </div>
                        <a href="{{ asset('uploads/' . $asetDesa->bukti_kepemilikan) }}" target="_blank" class="btn btn-primary mt-2 py-2 px-3 w-100 w-md-auto">
                            <i class="fas fa-eye me-1"></i> <span class="d-none d-md-inline">Lihat Gambar Asli</span><span class="d-inline d-md-none">Lihat</span>
                        </a>
                    @endif
                    
                    <p class="text-muted mt-3 mb-0">
                        <small>Terakhir diperbarui: {{ $asetDesa->updated_at->format('d F Y') }}</small>
                    </p>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-excel fa-4x text-muted mb-3"></i>
                        <p class="mb-0">Tidak ada dokumen bukti kepemilikan yang tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Foto Aset -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white p-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image me-2"></i>
                    Foto Aset
                </h5>
            </div>
            <div class="card-body text-center p-3 p-md-4">
                @if($asetDesa->foto)
                    <img src="{{ asset('uploads/' . $asetDesa->foto) }}" alt="Foto Aset" class="img-fluid rounded" style="max-height: 300px; max-width: 100%;">
                    <a href="{{ asset('uploads/' . $asetDesa->foto) }}" target="_blank" class="btn btn-primary mt-3 py-2 px-3 w-100 w-md-auto">
                        <i class="fas fa-eye me-1"></i> <span class="d-none d-md-inline">Lihat Foto Asli</span><span class="d-inline d-md-none">Lihat</span>
                    </a>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-camera fa-4x text-muted mb-3"></i>
                        <p class="mb-0">Tidak ada foto aset yang tersedia.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection