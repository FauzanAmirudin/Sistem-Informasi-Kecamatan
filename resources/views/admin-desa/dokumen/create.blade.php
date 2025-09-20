@extends('layouts.admin')

@section('page-title', 'Upload Dokumen Baru')

@section('page-actions')
<div class="btn-group" role="group">
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
                    <i class="fas fa-upload me-2"></i>
                    Form Upload Dokumen
                </h5>
            </div>
            <div class="card-body">
                @if(session('errors'))
                    <div class="alert alert-danger">
                        <strong>Error Upload:</strong>
                        <ul class="mb-0">
                            @foreach(session('errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin-desa.dokumen.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" required>
                        @error('nama_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih Kategori</option>
                            <option value="surat" {{ old('kategori') == 'surat' ? 'selected' : '' }}>Surat</option>
                            <option value="laporan" {{ old('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="peraturan" {{ old('kategori') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                            <option value="pedoman" {{ old('kategori') == 'pedoman' ? 'selected' : '' }}>Pedoman</option>
                            <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Berikan deskripsi singkat tentang dokumen ini.</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">Dokumen Publik</label>
                        </div>
                        <div class="form-text">Jika diaktifkan, dokumen ini dapat diakses oleh semua pengguna.</div>
                    </div>

                    <div class="mb-4">
                        <label for="files" class="form-label">Upload File <span class="text-danger">*</span></label>
                        <div class="dropzone-container border rounded p-3 @error('files') border-danger @enderror">
                            <div class="text-center py-4 dropzone-message">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                                <h5>Drag & drop file di sini</h5>
                                <p class="text-muted">atau</p>
                                <label for="files" class="btn btn-outline-primary">
                                    Pilih File
                                </label>
                                <input type="file" id="files" name="files[]" class="d-none" multiple onchange="previewFiles(this)">
                            </div>
                            <div id="file-preview" class="row g-2 mt-2"></div>
                        </div>
                        @error('files')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        @error('files.*')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB per file.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Upload Dokumen
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
    function previewFiles(input) {
        const preview = document.getElementById('file-preview');
        preview.innerHTML = '';
        
        if (input.files) {
            document.querySelector('.dropzone-message').classList.add('d-none');
            
            Array.from(input.files).forEach(file => {
                const col = document.createElement('div');
                col.className = 'col-md-4';
                
                const card = document.createElement('div');
                card.className = 'card h-100';
                
                const cardBody = document.createElement('div');
                cardBody.className = 'card-body p-2';
                
                // Icon based on file type
                let icon = 'fa-file';
                if (file.type.includes('image')) {
                    icon = 'fa-file-image';
                } else if (file.type.includes('pdf')) {
                    icon = 'fa-file-pdf';
                } else if (file.type.includes('word')) {
                    icon = 'fa-file-word';
                } else if (file.type.includes('excel') || file.type.includes('sheet')) {
                    icon = 'fa-file-excel';
                } else if (file.type.includes('powerpoint') || file.type.includes('presentation')) {
                    icon = 'fa-file-powerpoint';
                }
                
                // File size formatting
                let fileSize = file.size;
                let fileSizeFormatted = '';
                if (fileSize < 1024) {
                    fileSizeFormatted = fileSize + ' B';
                } else if (fileSize < 1024 * 1024) {
                    fileSizeFormatted = (fileSize / 1024).toFixed(2) + ' KB';
                } else {
                    fileSizeFormatted = (fileSize / (1024 * 1024)).toFixed(2) + ' MB';
                }
                
                cardBody.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <i class="fas ${icon} fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 text-truncate">
                            <div class="text-truncate" title="${file.name}">${file.name}</div>
                            <small class="text-muted">${fileSizeFormatted}</small>
                        </div>
                    </div>
                `;
                
                card.appendChild(cardBody);
                col.appendChild(card);
                preview.appendChild(col);
            });
            
            // Add a button to clear selection
            const clearCol = document.createElement('div');
            clearCol.className = 'col-12 mt-2';
            clearCol.innerHTML = `
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFiles()">
                    <i class="fas fa-times me-1"></i> Hapus Semua
                </button>
            `;
            preview.appendChild(clearCol);
        }
    }
    
    function clearFiles() {
        const input = document.getElementById('files');
        input.value = '';
        document.getElementById('file-preview').innerHTML = '';
        document.querySelector('.dropzone-message').classList.remove('d-none');
    }
    
    // Drag and drop functionality
    const dropzone = document.querySelector('.dropzone-container');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropzone.classList.add('border-primary', 'bg-light');
    }
    
    function unhighlight() {
        dropzone.classList.remove('border-primary', 'bg-light');
    }
    
    dropzone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('files').files = files;
        previewFiles(document.getElementById('files'));
    }
</script>
@endpush