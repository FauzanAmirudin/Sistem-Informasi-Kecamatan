@extends('layouts.admin-desa')

@section('page-title', 'Edit Profil Desa')
@section('page-subtitle', 'Edit profil desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.profil.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Profil Desa {{ $desa->nama_desa }}
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

                <form action="{{ route('admin-desa.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informasi Dasar</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_desa" class="form-label">Nama Desa</label>
                                        <input type="text" class="form-control @error('nama_desa') is-invalid @enderror" id="nama_desa" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}">
                                        @error('nama_desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kode_desa" class="form-label">Kode Desa</label>
                                        <input type="text" class="form-control @error('kode_desa') is-invalid @enderror" id="kode_desa" name="kode_desa" value="{{ old('kode_desa', $desa->kode_desa) }}">
                                        @error('kode_desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kepala_desa" class="form-label">Kepala Desa</label>
                                        <input type="text" class="form-control @error('kepala_desa') is-invalid @enderror" id="kepala_desa" name="kepala_desa" value="{{ old('kepala_desa', $desa->kepala_desa) }}">
                                        @error('kepala_desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sk_kepala_desa" class="form-label">SK Kepala Desa (PDF)</label>
                                        <input type="file" class="form-control @error('sk_kepala_desa') is-invalid @enderror" id="sk_kepala_desa" name="sk_kepala_desa" accept=".pdf">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah file</small>
                                        @if($desa->sk_kepala_desa)
                                        <div class="mt-2">
                                            <a href="{{ $desa->sk_kepala_desa_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf me-1"></i>
                                                Lihat File Saat Ini
                                            </a>
                                        </div>
                                        @endif
                                        @error('sk_kepala_desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $desa->alamat) }}">
                                        @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="kode_pos" class="form-label">Kode Pos</label>
                                        <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $desa->kode_pos) }}">
                                        @error('kode_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="luas_wilayah" class="form-label">Luas Wilayah (km²)</label>
                                        <input type="number" step="0.01" class="form-control @error('luas_wilayah') is-invalid @enderror" id="luas_wilayah" name="luas_wilayah" value="{{ old('luas_wilayah', $desa->luas_wilayah) }}">
                                        @error('luas_wilayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="latitude" class="form-label">Latitude</label>
                                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $desa->latitude) }}">
                                        <small class="text-muted">Contoh: -7.123456</small>
                                        @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="longitude" class="form-label">Longitude</label>
                                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $desa->longitude) }}">
                                        <small class="text-muted">Contoh: 110.123456</small>
                                        @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dokumen dan Informasi Tambahan -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Dokumen dan Informasi Tambahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="monografi_file" class="form-label">File Monografi Desa (PDF)</label>
                                        <input type="file" class="form-control @error('monografi_file') is-invalid @enderror" id="monografi_file" name="monografi_file" accept=".pdf">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah file</small>
                                        @if($desa->monografi_file)
                                        <div class="mt-2">
                                            <a href="{{ $desa->monografi_file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf me-1"></i>
                                                Lihat File Saat Ini
                                            </a>
                                        </div>
                                        @endif
                                        @error('monografi_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="komoditas_unggulan" class="form-label">Komoditas Unggulan</label>
                                        <textarea class="form-control @error('komoditas_unggulan') is-invalid @enderror" id="komoditas_unggulan" name="komoditas_unggulan" rows="3">{{ old('komoditas_unggulan', $desa->komoditas_unggulan) }}</textarea>
                                        <small class="text-muted">Pisahkan dengan koma untuk beberapa komoditas</small>
                                        @error('komoditas_unggulan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="kondisi_sosial_ekonomi" class="form-label">Kondisi Sosial Ekonomi</label>
                                        <textarea class="form-control @error('kondisi_sosial_ekonomi') is-invalid @enderror" id="kondisi_sosial_ekonomi" name="kondisi_sosial_ekonomi" rows="4">{{ old('kondisi_sosial_ekonomi', $desa->kondisi_sosial_ekonomi) }}</textarea>
                                        @error('kondisi_sosial_ekonomi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-secondary me-md-2">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection