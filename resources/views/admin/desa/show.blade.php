@extends('layouts.admin')

@section('page-title', 'Detail Desa: ' . $desa->nama_desa)

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.desa.edit', $desa) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin.desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <!-- Informasi Dasar -->
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Dasar
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

        <!-- Profil Desa -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-leaf me-2"></i>
                    Profil Desa
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Komoditas Unggulan:</h6>
                        <p class="text-muted">
                            {{ $desa->komoditas_unggulan ?: 'Belum diisi' }}
                        </p>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-bold">Kondisi Sosial Ekonomi:</h6>
                        <p class="text-muted">
                            {{ $desa->kondisi_sosial_ekonomi ?: 'Belum diisi' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Dokumen
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">SK Kepala Desa:</h6>
                        @if($desa->sk_kepala_desa)
                            <a href="{{ route('admin.desa.download-sk', $desa) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Download SK
                            </a>
                        @else
                            <span class="text-muted">Belum diupload</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Monografi Desa:</h6>
                        @if($desa->monografi_file)
                            <a href="{{ route('admin.desa.download-monografi', $desa) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>
                                Download Monografi
                            </a>
                        @else
                            <span class="text-muted">Belum diupload</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik dan Peta -->
    <div class="col-lg-4">
        <!-- Statistik -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Statistik
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-primary mb-0">{{ number_format($desa->penduduks->count()) }}</h4>
                            <small class="text-muted">Total Penduduk</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-success mb-0">{{ $desa->perangkatDesas->where('status', 'aktif')->count() }}</h4>
                            <small class="text-muted">Perangkat Desa</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-info mb-0">{{ $desa->asetDesas->where('is_current', true)->count() }}</h4>
                            <small class="text-muted">Aset Desa</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-light p-3 rounded">
                            <h4 class="text-warning mb-0">{{ $desa->asetTanahWargas->count() }}</h4>
                            <small class="text-muted">Aset Warga</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-bold">Gender Penduduk:</h6>
                        @php
                            $totalPenduduk = $desa->penduduks->count();
                            $pendudukPria = $desa->penduduks->where('jenis_kelamin', 'L')->count();
                            $pendudukWanita = $desa->penduduks->where('jenis_kelamin', 'P')->count();
                        @endphp
                        <div class="progress mb-2" style="height: 20px;">
                            @if($totalPenduduk > 0)
                                <div class="progress-bar bg-primary" style="width: {{ ($pendudukPria/$totalPenduduk)*100 }}%">
                                    {{ $pendudukPria }} Pria
                                </div>
                                <div class="progress-bar bg-danger" style="width: {{ ($pendudukWanita/$totalPenduduk)*100 }}%">
                                    {{ $pendudukWanita }} Wanita
                                </div>
                            @else
                                <div class="progress-bar bg-secondary" style="width: 100%">
                                    Belum ada data
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peta Lokasi -->
        @if($desa->latitude && $desa->longitude)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Lokasi Desa
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="desaMap" style="height: 300px; width: 100%;"></div>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Koordinat: {{ $desa->latitude }}, {{ $desa->longitude }}
                </small>
            </div>
        </div>
        @endif

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
                    <a href="{{ route('admin.penduduk.index', ['desa' => $desa->id]) }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>
                        Lihat Data Penduduk
                    </a>
                    <a href="{{ route('admin.perangkat-desa.index', ['desa' => $desa->id]) }}" 
                       class="btn btn-outline-success">
                        <i class="fas fa-user-tie me-2"></i>
                        Lihat Perangkat Desa
                    </a>
                    <a href="{{ route('admin.aset-desa.index', ['desa' => $desa->id]) }}" 
                       class="btn btn-outline-info">
                        <i class="fas fa-building me-2"></i>
                        Lihat Aset Desa
                    </a>
                    <a href="{{ route('admin.aset-tanah-warga.index', ['desa' => $desa->id]) }}" 
                       class="btn btn-outline-warning">
                        <i class="fas fa-map me-2"></i>
                        Lihat Aset Warga
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($desa->latitude && $desa->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('desaMap').setView([{{ $desa->latitude }}, {{ $desa->longitude }}], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add marker
    const marker = L.marker([{{ $desa->latitude }}, {{ $desa->longitude }}]).addTo(map);
    
    marker.bindPopup(`
        <div class="text-center">
            <h6 class="fw-bold">{{ $desa->nama_desa }}</h6>
            <p class="mb-1">{{ $desa->kepala_desa }}</p>
            <small class="text-muted">{{ $desa->alamat }}</small>
        </div>
    `);
});
</script>
@endif
@endpush