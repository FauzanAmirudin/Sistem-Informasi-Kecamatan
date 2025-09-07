@extends('layouts.admin')

@section('page-title', 'Tambah Data Desa')

@section('page-actions')
<a href="{{ route('admin.desa.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-1"></i>
    Kembali
</a>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2"></i>
                    Form Tambah Desa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.desa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Data Dasar -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_desa" class="form-label">Nama Desa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_desa') is-invalid @enderror" 
                                   id="nama_desa" name="nama_desa" value="{{ old('nama_desa') }}" 
                                   placeholder="Masukkan nama desa">
                            @error('nama_desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kode_desa" class="form-label">Kode Desa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_desa') is-invalid @enderror" 
                                   id="kode_desa" name="kode_desa" value="{{ old('kode_desa') }}" 
                                   placeholder="Contoh: BJ001">
                            @error('kode_desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kepala_desa" class="form-label">Kepala Desa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kepala_desa') is-invalid @enderror" 
                                   id="kepala_desa" name="kepala_desa" value="{{ old('kepala_desa') }}" 
                                   placeholder="Nama kepala desa">
                            @error('kepala_desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                   id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" 
                                   placeholder="Contoh: 30811">
                            @error('kode_pos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="3" 
                                  placeholder="Alamat lengkap desa">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Koordinat dan Luas -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                   id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                   step="any" placeholder="-2.9674">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                   id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                   step="any" placeholder="104.7294">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="luas_wilayah" class="form-label">Luas Wilayah (km²)</label>
                            <input type="number" class="form-control @error('luas_wilayah') is-invalid @enderror" 
                                   id="luas_wilayah" name="luas_wilayah" value="{{ old('luas_wilayah') }}" 
                                   step="0.01" placeholder="25.50">
                            @error('luas_wilayah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Profil Desa -->
                    <div class="mb-3">
                        <label for="komoditas_unggulan" class="form-label">Komoditas Unggulan</label>
                        <textarea class="form-control @error('komoditas_unggulan') is-invalid @enderror" 
                                  id="komoditas_unggulan" name="komoditas_unggulan" rows="2" 
                                  placeholder="Contoh: Padi, Kelapa Sawit, Karet">{{ old('komoditas_unggulan') }}</textarea>
                        @error('komoditas_unggulan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kondisi_sosial_ekonomi" class="form-label">Kondisi Sosial Ekonomi</label>
                        <textarea class="form-control @error('kondisi_sosial_ekonomi') is-invalid @enderror" 
                                  id="kondisi_sosial_ekonomi" name="kondisi_sosial_ekonomi" rows="3" 
                                  placeholder="Deskripsi kondisi sosial ekonomi masyarakat">{{ old('kondisi_sosial_ekonomi') }}</textarea>
                        @error('kondisi_sosial_ekonomi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload Files -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sk_kepala_desa" class="form-label">SK Kepala Desa</label>
                            <input type="file" class="form-control @error('sk_kepala_desa') is-invalid @enderror" 
                                   id="sk_kepala_desa" name="sk_kepala_desa" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Format: PDF, JPG, JPEG, PNG. Maksimal 2MB</div>
                            @error('sk_kepala_desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="monografi_file" class="form-label">File Monografi</label>
                            <input type="file" class="form-control @error('monografi_file') is-invalid @enderror" 
                                   id="monografi_file" name="monografi_file" accept=".pdf">
                            <div class="form-text">Format: PDF. Maksimal 5MB</div>
                            @error('monografi_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label">Status Desa</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.desa.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Panduan Pengisian
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Data Wajib:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>Nama Desa</li>
                        <li><i class="fas fa-check text-success me-2"></i>Kode Desa (Unik)</li>
                        <li><i class="fas fa-check text-success me-2"></i>Kepala Desa</li>
                        <li><i class="fas fa-check text-success me-2"></i>Alamat Lengkap</li>
                                                <li><i class="fas fa-check text-success me-2"></i>Kode Pos</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Data Opsional:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-info text-info me-2"></i>Koordinat GPS</li>
                        <li><i class="fas fa-info text-info me-2"></i>Luas Wilayah</li>
                        <li><i class="fas fa-info text-info me-2"></i>Komoditas Unggulan</li>
                        <li><i class="fas fa-info text-info me-2"></i>Kondisi Sosial Ekonomi</li>
                        <li><i class="fas fa-info text-info me-2"></i>File SK & Monografi</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <small>
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Tips:</strong> Koordinat GPS dapat diperoleh dari Google Maps atau GPS tracker untuk menampilkan lokasi desa di peta monitoring.
                    </small>
                </div>
            </div>
        </div>

        <!-- Map Preview -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-map me-2"></i>
                    Preview Lokasi
                </h6>
            </div>
            <div class="card-body p-0">
                <div id="mapPreview" style="height: 200px; width: 100%;"></div>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    Klik pada peta untuk mendapatkan koordinat atau masukkan koordinat manual di form.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('mapPreview').setView([-2.9674, 104.7294], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    // Click handler untuk mendapatkan koordinat
    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        
        // Update input fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Add or update marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
        
        marker.bindPopup(`Koordinat: ${lat}, ${lng}`).openPopup();
    });

    // Update marker when coordinates are manually entered
    document.getElementById('latitude').addEventListener('change', updateMarker);
    document.getElementById('longitude').addEventListener('change', updateMarker);

    function updateMarker() {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);
        
        if (lat && lng) {
            const latlng = L.latLng(lat, lng);
            
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng).addTo(map);
            }
            
            map.setView(latlng, 13);
            marker.bindPopup(`Koordinat: ${lat}, ${lng}`);
        }
    }
});
</script>
@endpush
                