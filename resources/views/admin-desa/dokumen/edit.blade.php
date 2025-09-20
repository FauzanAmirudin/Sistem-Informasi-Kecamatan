@extends('layouts.admin')

@section('page-title', 'Edit Dokumen')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.dokumen.show', $dokuman) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
    <a href="{{ route('admin-desa.dokumen.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Dokumen
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin-desa.dokumen.update', $dokuman) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen', $dokuman->nama_dokumen) }}" required>
                        @error('nama_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
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

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $dokuman->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Berikan deskripsi singkat tentang dokumen ini.</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $dokuman->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Dokumen Publik</label>
                        </div>
                        <div class="form-text">Jika diaktifkan, dokumen ini dapat diakses oleh semua pengguna.</div>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label">File Dokumen</label>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title">File Saat Ini:</h6>
                                @php
                                    $extension = pathinfo($dokuman->file_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                    $isPdf = strtolower($extension) === 'pdf';
                                    $fileName = basename($dokuman->file_path);
                                @endphp

                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($isImage)
                                            <i class="fas fa-file-image fa-2x text-primary"></i>
                                        @elseif($isPdf)
                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                        @elseif(strpos($dokuman->file_type, 'word') !== false)
                                            <i class="fas fa-file-word fa-2x text-primary"></i>
                                        @elseif(strpos($dokuman->file_type, 'excel') !== false || strpos($dokuman->file_type, 'spreadsheet') !== false)
                                            <i class="fas fa-file-excel fa-2x text-success"></i>
                                        @elseif(strpos($dokuman->file_type, 'powerpoint') !== false || strpos($dokuman->file_type, 'presentation') !== false)
                                            <i class="fas fa-file-powerpoint fa-2x text-warning"></i>
                                        @else
                                            <i class="fas fa-file fa-2x text-secondary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="mb-1">{{ $fileName }}</p>
                                        <p class="mb-0 text-muted small">{{ $dokuman->file_size_formatted }} | {{ strtoupper($extension) }}</p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ Storage::url($dokuman->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Lihat File
                                    </a>
                                    <a href="{{ route('admin-desa.dokumen.download', $dokuman) }}" class="btn btn-sm btn-outline-success ms-1">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="input-group @error('file') is-invalid @enderror">
                            <input type="file" class="form-control" id="file" name="file">
                            <label class="input-group-text" for="file">Upload</label>
                        </div>
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Biarkan kosong jika tidak ingin mengubah file. Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin-desa.dokumen.show', $dokuman) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
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