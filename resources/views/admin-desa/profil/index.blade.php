@extends('layouts.admin-desa')

@section('page-title', 'Profil Desa')
@section('page-subtitle', 'Informasi dan data profil desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.profil.edit') }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit Profil
    </a>
</div>
@endsection

@section('admin-content')
<!-- Informasi Profil Desa -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-home me-2"></i>
                    Profil Desa {{ $desa->nama_desa }}
                </h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td style="width: 30%" class="fw-bold">Nama Desa:</td>
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
                                <td>{{ $desa->kode_pos ?: 'Belum diisi' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td style="width: 30%" class="fw-bold">Luas Wilayah:</td>
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
</div>

<!-- Informasi Tambahan -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
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
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
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
                                        <i class="fas fa-eye me-1"></i> Lihat
                                    </a>
                                    <a href="{{ route('admin-desa.profil.download-monografi') }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download me-1"></i> Unduh
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
</div>

<!-- Peta Lokasi Desa -->
@if($desa->latitude && $desa->longitude)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Lokasi Desa
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($desa->latitude && $desa->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap&v={{ time() }}" async defer></script>
<script>
    function initMap() {
        // Menggunakan nilai latitude dan longitude terbaru dari server
        const desaLocation = { 
            lat: parseFloat({{ $desa->latitude }}), 
            lng: parseFloat({{ $desa->longitude }}) 
        };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: desaLocation,
        });
        
        const marker = new google.maps.Marker({
            position: desaLocation,
            map: map,
            title: "{{ $desa->nama_desa }}"
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: '<div><strong>{{ $desa->nama_desa }}</strong><br>{{ $desa->alamat }}</div>'
        });
        
        marker.addListener("click", () => {
            infoWindow.open(map, marker);
        });
        
        // Open info window by default
        infoWindow.open(map, marker);
    }
</script>
@endif
@endpush