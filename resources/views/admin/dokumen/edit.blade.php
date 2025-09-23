@extends('layouts.admin')

@section('page-title', 'Edit Dokumen')
@section('page-subtitle', 'Edit dokumen')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.dokumen.show', $dokuman) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
    <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Dokumen
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.dokumen.update', $dokuman) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Desa -->
                    <div class="mb-3">
                        <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                        <select class="form-select @error('desa_id') is-invalid @enderror" id="desa_id" name="desa_id" required>
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ old('desa_id', $dokuman->desa_id) == $desa->id ? 'selected' : '' }}>
                                    {{ $desa->nama_desa }}
                                </option>
                            @endforeach
                        </select>
                        @error('desa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Nama Dokumen -->
                    <div class="mb-3">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" 
                               id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen', $dokuman->nama_dokumen) }}" 
                               placeholder="Masukkan nama dokumen" required>
                        @error('nama_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Kategori -->
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="surat" {{ old('kategori', $dokuman->kategori) == 'surat' ? 'selected' : '' }}>Surat</option>
                            <option value="laporan" {{ old('kategori', $dokuman->kategori) == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="peraturan" {{ old('kategori', $dokuman->kategori) == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                            <option value="pedoman" {{ old('kategori', $dokuman->kategori) == 'pedoman' ? 'selected' : '' }}>Pedoman</option>
                            <option value="lainnya" {{ old('kategori', $dokuman->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi singkat tentang dokumen">{{ old('deskripsi', $dokuman->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- File Saat Ini -->
                    <div class="mb-3">
                        <label class="form-label">File Saat Ini</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    @php
                                        $icon = 'fa-file';
                                        $fileType = $dokuman->file_type;
                                        
                                        if (strpos($fileType, 'pdf') !== false) {
                                            $icon = 'fa-file-pdf';
                                        } elseif (strpos($fileType, 'image') !== false) {
                                            $icon = 'fa-file-image';
                                        } elseif (strpos($fileType, 'word') !== false) {
                                            $icon = 'fa-file-word';
                                        } elseif (strpos($fileType, 'excel') !== false || strpos($fileType, 'spreadsheet') !== false) {
                                            $icon = 'fa-file-excel';
                                        } elseif (strpos($fileType, 'powerpoint') !== false || strpos($fileType, 'presentation') !== false) {
                                            $icon = 'fa-file-powerpoint';
                                        }
                                    @endphp
                                    <i class="fas {{ $icon }} fa-2x me-3 text-primary"></i>
                                    <div>
                                        <h6 class="mb-0">{{ basename($dokuman->file_path) }}</h6>
                                        <div class="small text-muted">
                                            {{ $dokuman->file_size_formatted }} | 
                                            {{ strtoupper(pathinfo($dokuman->file_path, PATHINFO_EXTENSION)) }}
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="{{ $dokuman->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            Lihat
                                        </a>
                                        <a href="{{ route('admin.dokumen.download', $dokuman) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download me-1"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upload File Baru (Opsional) -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File Baru (Opsional)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <div class="form-text">
                            Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB.
                            Kosongkan jika tidak ingin mengubah file.
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Visibility -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                   {{ old('is_public', $dokuman->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">
                                Dokumen dapat diakses publik
                            </label>
                            <div class="form-text">Jika dicentang, dokumen dapat diakses oleh semua pengguna. Jika tidak, hanya admin yang dapat mengakses.</div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>
                            Update Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Info Panel -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Dokumen
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small>
                        <strong>Diupload oleh:</strong><br>
                        {{ $dokuman->uploader->name ?? 'Tidak diketahui' }}
                    </small>
                </div>
                <div class="mb-3">
                    <small>
                        <strong>Tanggal Upload:</strong><br>
                        {{ $dokuman->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="mb-3">
                    <small>
                        <strong>Terakhir Diubah:</strong><br>
                        {{ $dokuman->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="mb-3">
                    <small>
                        <strong>Status:</strong><br>
                        <span class="badge {{ $dokuman->is_public ? 'bg-success' : 'bg-secondary' }}">
                            {{ $dokuman->is_public ? 'Publik' : 'Private' }}
                        </span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection