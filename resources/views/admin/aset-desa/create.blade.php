@extends('layouts.admin')

@section('page-title', 'Tambah Aset Desa')
@section('page-subtitle', 'Tambah data aset desa baru')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-plus-circle me-2"></i>
                    Form Tambah Aset Desa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.aset-desa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                                <select class="form-select @error('desa_id') is-invalid @enderror" id="desa_id" name="desa_id" required>
                                    <option value="" selected disabled>-- Pilih Desa --</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ old('desa_id') == $desa->id ? 'selected' : '' }}>
                                            {{ $desa->nama_desa }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('desa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kategori_aset" class="form-label">Kategori Aset <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategori_aset') is-invalid @enderror" id="kategori_aset" name="kategori_aset" required>
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    <option value="tanah" {{ old('kategori_aset') == 'tanah' ? 'selected' : '' }}>Tanah</option>
                                    <option value="bangunan" {{ old('kategori_aset') == 'bangunan' ? 'selected' : '' }}>Bangunan</option>
                                    <option value="inventaris" {{ old('kategori_aset') == 'inventaris' ? 'selected' : '' }}>Inventaris</option>
                                </select>
                                @error('kategori_aset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_aset" class="form-label">Nama Aset <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_aset') is-invalid @enderror" id="nama_aset" name="nama_aset" value="{{ old('nama_aset') }}" required>
                        @error('nama_aset')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nilai_perolehan" class="form-label">Nilai Perolehan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_perolehan') is-invalid @enderror" id="nilai_perolehan" name="nilai_perolehan" value="{{ old('nilai_perolehan') }}" required>
                                @error('nilai_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nilai_sekarang" class="form-label">Nilai Sekarang (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_sekarang') is-invalid @enderror" id="nilai_sekarang" name="nilai_sekarang" value="{{ old('nilai_sekarang') }}" required>
                                @error('nilai_sekarang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_perolehan" class="form-label">Tanggal Perolehan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_perolehan') is-invalid @enderror" id="tanggal_perolehan" name="tanggal_perolehan" value="{{ old('tanggal_perolehan') }}" required>
                                @error('tanggal_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                    <option value="" selected disabled>-- Pilih Kondisi --</option>
                                    <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required>
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="bukti_kepemilikan" class="form-label">Bukti Kepemilikan (PDF/JPG/PNG)</label>
                        <input type="file" class="form-control @error('bukti_kepemilikan') is-invalid @enderror" id="bukti_kepemilikan" name="bukti_kepemilikan" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Unggah file bukti kepemilikan (opsional, maks. 2MB)</small>
                        @error('bukti_kepemilikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-calculate nilai_sekarang based on nilai_perolehan initially
    document.addEventListener('DOMContentLoaded', function() {
        const nilaiPerolehan = document.getElementById('nilai_perolehan');
        const nilaiSekarang = document.getElementById('nilai_sekarang');
        
        // Only set nilai_sekarang if it's empty and nilai_perolehan has a value
        if (nilaiPerolehan.value && !nilaiSekarang.value) {
            nilaiSekarang.value = nilaiPerolehan.value;
        }
        
        // Update nilai_sekarang when nilai_perolehan changes (if user hasn't manually set nilai_sekarang)
        nilaiPerolehan.addEventListener('input', function() {
            // Only auto-update if user hasn't manually edited nilai_sekarang
            if (!nilaiSekarang.dataset.userEdited) {
                nilaiSekarang.value = this.value;
            }
        });
        
        // Mark nilai_sekarang as user-edited when user changes it
        nilaiSekarang.addEventListener('input', function() {
            this.dataset.userEdited = 'true';
        });
    });
</script>
@endpush