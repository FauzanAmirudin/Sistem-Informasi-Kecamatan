@extends('layouts.admin')

@section('page-title', 'Monitoring Desa')

@section('page-actions')
<div class="btn-group" role="group">
    <button type="button" class="btn btn-outline-primary" onclick="refreshMap()">
        <i class="fas fa-sync-alt me-1"></i>
        Refresh
    </button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<!-- Status Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold" id="countHijau">0</div>
                        <div class="small">Desa Update Terbaru</div>
                        <div class="small opacity-75">(≤ 7 hari)</div>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold" id="countKuning">0</div>
                        <div class="small">Desa Perlu Update</div>
                        <div class="small opacity-75">(8-30 hari)</div>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold" id="countMerah">0</div>
                        <div class="small">Desa Butuh Perhatian</div>
                        <div class="small opacity-75">(> 30 hari)</div>
                    </div>
                    <i class="fas fa-times-circle fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map dan Daftar Desa -->
<div class="row">
    <!-- Peta Interaktif -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                    Peta Sebaran Desa
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="mapView" id="satellite" checked>
                    <label class="btn btn-outline-primary" for="satellite">Satelit</label>
                    
                    <input type="radio" class="btn-check" name="mapView" id="street">
                    <label class="btn btn-outline-primary" for="street">Jalan</label>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
            <div class="card-footer bg-light">
                <div class="row text-center">
                    <div class="col-4">
                        <i class="fas fa-circle text-success me-2"></i>
                        <small>Update Terbaru</small>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-circle text-warning me-2"></i>
                        <small>Perlu Update</small>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-circle text-danger me-2"></i>
                        <small>Butuh Perhatian</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Desa -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2 text-secondary"></i>
                    Daftar Desa
                </h5>
                <span class="badge bg-primary">{{ $desas->count() }} Desa</span>
            </div>
            <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                <div class="list-group list-group-flush">
                    @foreach($desas as $desa)
                    <div class="list-group-item list-group-item-action desa-item" 
                         data-lat="{{ $desa->latitude }}" 
                         data-lng="{{ $desa->longitude }}"
                         data-status="{{ $desa->status_update }}"
                         style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">
                                    <i class="fas fa-circle status-{{ $desa->status_update }} me-2"></i>
                                    {{ $desa->nama_desa }}
                                </h6>
                                <p class="mb-1 text-muted small">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $desa->kepala_desa }}
                                </p>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-users me-1"></i>
                                            {{ number_format($desa->total_penduduk) }} jiwa
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $desa->total_perangkat }} perangkat
                                        </small>
                                    </div>
                                </div>
                                @if($desa->last_updated_at)
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Update: {{ $desa->last_updated_at->diffForHumans() }}
                                    </small>
                                @else
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        Belum pernah update
                                    </small>
                                @endif
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.desa.show', $desa) }}">
                                            <i class="fas fa-eye me-2"></i>Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.desa.edit', $desa) }}">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.penduduk.index', ['desa' => $desa->id]) }}">
                                            <i class="fas fa-users me-2"></i>Data Penduduk
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reminder Desa yang Perlu Update -->
@php
    $desaPerluUpdate = $desas->filter(function($desa) {
        return in_array($desa->status_update, ['kuning', 'merah']);
    });
@endphp

@if($desaPerluUpdate->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bell me-2"></i>
                    Reminder: Desa yang Perlu Diperhatikan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($desaPerluUpdate as $desa)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="alert alert-{{ $desa->status_update == 'kuning' ? 'warning' : 'danger' }} mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $desa->nama_desa }}</strong><br>
                                    <small>
                                        @if($desa->last_updated_at)
                                            Terakhir update: {{ $desa->last_updated_at->diffForHumans() }}
                                        @else
                                            Belum pernah update
                                        @endif
                                    </small>
                                </div>
                                <a href="{{ route('admin.desa.edit', $desa) }}" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data desa dari server
    const desaData = @json($desas->map(function($desa) {
        return [
            'id' => $desa->id,
            'nama' => $desa->nama_desa,
            'kepala' => $desa->kepala_desa,
            'lat' => $desa->latitude,
            'lng' => $desa->longitude,
            'penduduk' => $desa->total_penduduk,
            'perangkat' => $desa->total_perangkat,
            'status' => $desa->status_update,
            'last_update' => $desa->last_updated_at ? $desa->last_updated_at->diffForHumans() : 'Belum pernah update',
            'alamat' => $desa->alamat
        ];
    }));

    // Inisialisasi peta
    const map = L.map('map').setView([-2.9674, 104.7294], 12);
    
    // Layer peta
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });
    
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    // Set layer default
    satelliteLayer.addTo(map);

    // Switch layer
    document.getElementById('satellite').addEventListener('change', function() {
        if (this.checked) {
            map.removeLayer(streetLayer);
            map.addLayer(satelliteLayer);
        }
    });

    document.getElementById('street').addEventListener('change', function() {
        if (this.checked) {
            map.removeLayer(satelliteLayer);
            map.addLayer(streetLayer);
        }
    });

    // Marker untuk setiap desa
    const markers = [];
    let statusCount = {hijau: 0, kuning: 0, merah: 0};

    desaData.forEach(function(desa) {
        if (desa.lat && desa.lng) {
            // Tentukan warna marker berdasarkan status
            let markerColor = '#dc3545'; // merah default
            if (desa.status === 'hijau') {
                markerColor = '#198754';
                statusCount.hijau++;
            } else if (desa.status === 'kuning') {
                markerColor = '#ffc107';
                statusCount.kuning++;
            } else {
                statusCount.merah++;
            }

            // Custom icon
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            // Buat marker
            const marker = L.marker([desa.lat, desa.lng], {icon: customIcon}).addTo(map);

            // Popup content
            const popupContent = `
                <div class="popup-content">
                    <h6 class="fw-bold mb-2">${desa.nama}</h6>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            <strong>Kepala Desa:</strong> ${desa.kepala}
                        </small>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>
                                ${desa.penduduk.toLocaleString()} jiwa
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-user-tie me-1"></i>
                                ${desa.perangkat} perangkat
                            </small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            ${desa.alamat}
                        </small>
                    </div>
                    <div class="mb-3">
                        <small class="status-${desa.status}">
                            <i class="fas fa-clock me-1"></i>
                            ${desa.last_update}
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/admin/desa/${desa.id}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Detail
                        </a>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent, {
                maxWidth: 250,
                className: 'custom-popup'
            });

            markers.push({marker: marker, desa: desa});
        }
    });

    // Update counter
    document.getElementById('countHijau').textContent = statusCount.hijau;
    document.getElementById('countKuning').textContent = statusCount.kuning;
    document.getElementById('countMerah').textContent = statusCount.merah;

    // Click handler untuk daftar desa
    document.querySelectorAll('.desa-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            
            if (lat && lng) {
                map.setView([lat, lng], 15);
                
                // Find and open popup
                markers.forEach(function(markerData) {
                    if (markerData.desa.lat == lat && markerData.desa.lng == lng) {
                        markerData.marker.openPopup();
                    }
                });
            }
        });
    });

    // Refresh function
    window.refreshMap = function() {
        showLoading();
        setTimeout(function() {
            location.reload();
        }, 1000);
    };
});
</script>

<style>
.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.popup-content {
    min-width: 200px;
}

.custom-marker {
    border: none !important;
    background: none !important;
}

.desa-item:hover {
    background-color: #f8f9fa !important;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endpush