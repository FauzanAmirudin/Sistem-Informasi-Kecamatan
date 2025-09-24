@extends('layouts.admin-desa')

@section('page-title', 'Edit Aset: ' . $asetDesa->nama_aset)
@section('page-subtitle', 'Edit data aset desa')

@section('page-actions')
<div class="btn-group d-flex flex-wrap" role="group">
    <a href="{{ route('admin-desa.aset-desa.show', $asetDesa) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        <span class="d-none d-md-inline">Lihat Detail</span>
    </a>
    <a href="{{ route('admin-desa.aset-desa.riwayat', $asetDesa) }}" class="btn btn-secondary">
        <i class="fas fa-history me-1"></i>
        <span class="d-none d-md-inline">Riwayat</span>
    </a>
    <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Aset Desa
                </h5>
            </div>
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('admin-desa.aset-desa.update', $asetDesa) }}" method="POST" enctype="multipart/form-data" id="updateForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_method" value="PUT">
                    
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <!-- Alasan Update -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info p-3 p-md-4">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading mb-2">Alasan Perubahan Data</h5>
                                        <p class="mb-0">Silakan isi alasan perubahan data untuk keperluan pencatatan riwayat.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 mb-md-4">
                                <label for="update_reason" class="form-label mb-2">Alasan Update <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('update_reason') is-invalid @enderror" 
                                          id="update_reason" name="update_reason" rows="2" required>{{ old('update_reason') }}</textarea>
                                @error('update_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: Perbaikan data, Perubahan kondisi, Pembaruan nilai, dll.</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Data Dasar</h5>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                            <div class="mb-3">
                                <label for="nama_aset" class="form-label">Nama Aset <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_aset') is-invalid @enderror" 
                                       id="nama_aset" name="nama_aset" value="{{ old('nama_aset', $asetDesa->nama_aset) }}" required>
                                @error('nama_aset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="kategori_aset" class="form-label">Kategori Aset <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_aset') is-invalid @enderror" 
                                        id="kategori_aset" name="kategori_aset" required>
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option value="tanah" {{ old('kategori_aset', $asetDesa->kategori_aset) == 'tanah' ? 'selected' : '' }}>Tanah</option>
                                    <option value="bangunan" {{ old('kategori_aset', $asetDesa->kategori_aset) == 'bangunan' ? 'selected' : '' }}>Bangunan</option>
                                    <option value="inventaris" {{ old('kategori_aset', $asetDesa->kategori_aset) == 'inventaris' ? 'selected' : '' }}>Inventaris</option>
                                </select>
                                @error('kategori_aset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <input type="hidden" name="desa_id" value="{{ $desa->id }}">
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $asetDesa->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Nilai dan Kondisi -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Nilai dan Kondisi</h5>
                        </div>
                        
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="mb-3">
                                <label for="nilai_perolehan" class="form-label">Nilai Perolehan (Rp)</label>
                                <input type="number" class="form-control @error('nilai_perolehan') is-invalid @enderror" 
                                       id="nilai_perolehan" name="nilai_perolehan" value="{{ old('nilai_perolehan', $asetDesa->nilai_perolehan) }}" min="0" step="1000">
                                @error('nilai_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="mb-3">
                                <label for="nilai_sekarang" class="form-label">Nilai Sekarang (Rp)</label>
                                <input type="number" class="form-control @error('nilai_sekarang') is-invalid @enderror" 
                                       id="nilai_sekarang" name="nilai_sekarang" value="{{ old('nilai_sekarang', $asetDesa->nilai_sekarang) }}" min="0" step="1000">
                                @error('nilai_sekarang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="mb-3">
                                <label for="tanggal_perolehan" class="form-label">Tanggal Perolehan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_perolehan') is-invalid @enderror" 
                                       id="tanggal_perolehan" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', $asetDesa->tanggal_perolehan->format('Y-m-d')) }}" required>
                                @error('tanggal_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-12 mb-3">
                            <div class="mb-3">
                                <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                <select class="form-select @error('kondisi') is-invalid @enderror" 
                                        id="kondisi" name="kondisi" required>
                                    <option value="" disabled>Pilih Kondisi</option>
                                    <option value="baik" {{ old('kondisi', $asetDesa->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi', $asetDesa->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi', $asetDesa->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-8 col-sm-12">
                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                       id="lokasi" name="lokasi" value="{{ old('lokasi', $asetDesa->lokasi) }}" required>
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Tambahan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">Data Tambahan</h5>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 mb-3 mb-md-0">
                            <div class="mb-3">
                                <label for="bukti_kepemilikan" class="form-label">Bukti Kepemilikan</label>
                                <input type="file" class="form-control @error('bukti_kepemilikan') is-invalid @enderror" 
                                       id="bukti_kepemilikan" name="bukti_kepemilikan">
                                @error('bukti_kepemilikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG. Maks: 2MB</small>
                                
                                @if($asetDesa->bukti_kepemilikan)
                                <div class="mt-2">
                                    <p class="mb-1">Dokumen saat ini:</p>
                                    <a href="{{ $asetDesa->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file me-1"></i>
                                        <span class="d-none d-md-inline">Lihat Dokumen</span>
                                        <span class="d-inline d-md-none">Lihat</span>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $asetDesa->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-2">
                        <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-secondary me-md-2 w-100 w-md-auto mb-2 mb-md-0 py-2 px-4">
                            <i class="fas fa-times-circle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-warning w-100 w-md-auto py-2 px-4">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection