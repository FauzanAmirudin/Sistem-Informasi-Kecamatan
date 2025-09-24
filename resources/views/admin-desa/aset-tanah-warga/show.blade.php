@extends('layouts.admin-desa')

@section('page-title', 'Detail SPH Aset Tanah Warga')
@section('page-subtitle', 'Detail data aset tanah warga')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.aset-tanah-warga.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin-desa.aset-tanah-warga.edit', $asetTanahWarga) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
        <i class="fas fa-trash me-1"></i>
        Hapus
    </button>
</div>
@endsection

@section('admin-content')
<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data SPH dengan nomor <strong>{{ $asetTanahWarga->nomor_sph }}</strong> milik <strong>{{ $asetTanahWarga->nama_pemilik }}</strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin-desa.aset-tanah-warga.destroy', $asetTanahWarga) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Informasi Umum -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Pemilik</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold">Nama Pemilik</td>
                        <td width="60%">{{ $asetTanahWarga->nama_pemilik }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">NIK Pemilik</td>
                        <td>{{ $asetTanahWarga->nik_pemilik }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Desa</td>
                        <td>{{ $asetTanahWarga->desa->nama_desa }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi SPH</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold">Nomor SPH</td>
                        <td width="60%">{{ $asetTanahWarga->nomor_sph }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal SPH</td>
                        <td>{{ $asetTanahWarga->tanggal_sph->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Status Kepemilikan</td>
                        <td>
                            @php
                                $statusLabel = '';
                                $badgeClass = 'bg-primary';
                                
                                switch($asetTanahWarga->status_kepemilikan) {
                                    case 'milik_sendiri':
                                        $statusLabel = 'Milik Sendiri';
                                        break;
                                    case 'warisan':
                                        $statusLabel = 'Warisan';
                                        break;
                                    case 'hibah':
                                        $statusLabel = 'Hibah';
                                        break;
                                    case 'jual_beli':
                                        $statusLabel = 'Jual Beli';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Bukti Kepemilikan</td>
                        <td>
                            @if($asetTanahWarga->bukti_kepemilikan)
                                <a href="{{ asset('uploads/' . $asetTanahWarga->bukti_kepemilikan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf me-1"></i> Lihat Dokumen
                                </a>
                            @else
                                <span class="text-muted">Tidak ada dokumen</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Detail Tanah -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Detail Tanah</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold">Jenis Tanah</td>
                        <td width="60%">
                            @php
                                $jenisTanahLabel = '';
                                $badgeClass = '';
                                
                                switch($asetTanahWarga->jenis_tanah) {
                                    case 'tanah_kering':
                                        $jenisTanahLabel = 'Tanah Kering';
                                        $badgeClass = 'bg-warning text-dark';
                                        break;
                                    case 'tanah_sawah':
                                        $jenisTanahLabel = 'Tanah Sawah';
                                        $badgeClass = 'bg-success';
                                        break;
                                    case 'tanah_pekarangan':
                                        $jenisTanahLabel = 'Tanah Pekarangan';
                                        $badgeClass = 'bg-info';
                                        break;
                                    case 'tanah_perkebunan':
                                        $jenisTanahLabel = 'Tanah Perkebunan';
                                        $badgeClass = 'bg-primary';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $jenisTanahLabel }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Luas Tanah</td>
                        <td>{{ number_format($asetTanahWarga->luas_tanah, 2, ',', '.') }} m²</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nilai Per Meter</td>
                        <td>Rp {{ number_format($asetTanahWarga->nilai_per_meter, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Nilai Total</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($asetTanahWarga->getNilaiTanahAttribute(), 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="fw-bold">Lokasi/Alamat</td>
                        <td width="60%">{{ $asetTanahWarga->lokasi }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Keterangan</td>
                        <td>{{ $asetTanahWarga->keterangan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Tanggal Dibuat</td>
                        <td>{{ $asetTanahWarga->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Terakhir Diperbarui</td>
                        <td>{{ $asetTanahWarga->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection