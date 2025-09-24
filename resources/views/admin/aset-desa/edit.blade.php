@extends('layouts.admin')

@section('page-title', 'Edit Aset Desa')
@section('page-subtitle', 'Edit data aset desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin.aset-desa.show', $asetDesa) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Detail
    </a>
</div>
@endsection

@section('admin-content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Aset Desa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.aset-desa.update', $asetDesa) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                                <select class="form-select @error('desa_id') is-invalid @enderror" id="desa_id" name="desa_id" required>
                                    <option value="" disabled>-- Pilih Desa --</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ (old('desa_id', $asetDesa->desa_id) == $desa->id) ? 'selected' : '' }}>
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
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <option value="tanah" {{ old('kategori_aset', $asetDesa->kategori_aset) == 'tanah' ? 'selected' : '' }}>Tanah</option>
                                    <option value="bangunan" {{ old('kategori_aset', $asetDesa->kategori_aset) == 'bangunan' ? 'selected' : '' }}>Bangunan</option>
                                    <option value="inventaris" {{ old('kategori_aset', $asetDesa->kategori_aset) == 'inventaris' ? 'selected' : '' }}>Inventaris</option>
                                </select>
                                @error('kategori_aset')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_aset" class="form-label">Nama Aset <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_aset') is-invalid @enderror" id="nama_aset" name="nama_aset" value="{{ old('nama_aset', $asetDesa->nama_aset) }}" required>
                        @error('nama_aset')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nilai_perolehan" class="form-label">Nilai Perolehan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_perolehan') is-invalid @enderror" id="nilai_perolehan" name="nilai_perolehan" value="{{ old('nilai_perolehan', $asetDesa->nilai_perolehan) }}" required>
                                @error('nilai_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nilai_sekarang" class="form-label">Nilai Sekarang (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('nilai_sekarang') is-invalid @enderror" id="nilai_sekarang" name="nilai_sekarang" value="{{ old('nilai_sekarang', $asetDesa->nilai_sekarang) }}" required>
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
                                <input type="date" class="form-control @error('tanggal_perolehan') is-invalid @enderror" id="tanggal_perolehan" name="tanggal_perolehan" value="{{ old('tanggal_perolehan', $asetDesa->tanggal_perolehan->format('Y-m-d')) }}" required>
                                @error('tanggal_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                    <option value="" disabled>-- Pilih Kondisi --</option>
                                    <option value="baik" {{ old('kondisi', $asetDesa->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="rusak_ringan" {{ old('kondisi', $asetDesa->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="rusak_berat" {{ old('kondisi', $asetDesa->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $asetDesa->lokasi) }}" required>
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="bukti_kepemilikan" class="form-label">Bukti Kepemilikan (PDF/JPG/PNG)</label>
                        <input type="file" class="form-control @error('bukti_kepemilikan') is-invalid @enderror" id="bukti_kepemilikan" name="bukti_kepemilikan" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Unggah file bukti kepemilikan baru (opsional, maks. 2MB)</small>
                        
                        @if($asetDesa->bukti_kepemilikan)
                        <div class="mt-2">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">File Saat Ini</span>
                                <a href="{{ $asetDesa->file_url }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Lihat Bukti Kepemilikan
                                </a>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="hapus_bukti" name="hapus_bukti" value="1">
                                <label class="form-check-label text-danger" for="hapus_bukti">
                                    Hapus bukti kepemilikan saat ini
                                </label>
                            </div>
                        </div>
                        @else
                        <div class="mt-2">
                            <span class="badge bg-secondary">Belum ada file bukti kepemilikan</span>
                        </div>
                        @endif
                        
                        @error('bukti_kepemilikan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $asetDesa->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="update_reason" class="form-label">Alasan Perubahan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('update_reason') is-invalid @enderror" id="update_reason" name="update_reason" rows="2" required>{{ old('update_reason') }}</textarea>
                        <small class="text-muted">Berikan alasan mengapa data ini diubah (untuk riwayat perubahan)</small>
                        @error('update_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
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

@push('scripts')
<script>
    // Disable nilai_sekarang field if hapus_bukti is checked
    document.addEventListener('DOMContentLoaded', function() {
        const hapusBukti = document.getElementById('hapus_bukti');
        const buktiKepemilikan = document.getElementById('bukti_kepemilikan');
        
        if (hapusBukti) {
            hapusBukti.addEventListener('change', function() {
                if (this.checked) {
                    buktiKepemilikan.disabled = true;
                    buktiKepemilikan.value = '';
                } else {
                    buktiKepemilikan.disabled = false;
                }
            });
        }
        
        // If file input has a value, uncheck the delete checkbox
        if (buktiKepemilikan && hapusBukti) {
            buktiKepemilikan.addEventListener('change', function() {
                if (this.value) {
                    hapusBukti.checked = false;
                }
            });
        }
    });
</script>
@endpush