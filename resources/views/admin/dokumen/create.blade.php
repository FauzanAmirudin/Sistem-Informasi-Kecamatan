@extends('layouts.admin')

@section('page-title', 'Upload Dokumen Baru')
@section('page-subtitle', 'Upload dokumen baru')

@section('page-actions')
<a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-file-upload me-2"></i>
                    Form Upload Dokumen
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    
                    <!-- Desa -->
                    <div class="mb-3">
                        <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                        <select class="form-select @error('desa_id') is-invalid @enderror" id="desa_id" name="desa_id" required>
                            <option value="">-- Pilih Desa --</option>
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
                    
                    <!-- Nama Dokumen -->
                    <div class="mb-3">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_dokumen') is-invalid @enderror" 
                               id="nama_dokumen" name="nama_dokumen" value="{{ old('nama_dokumen') }}" 
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
                    
                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Deskripsi singkat tentang dokumen">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Upload File -->
                    <div class="mb-3">
                        <label for="files" class="form-label">Upload File <span class="text-danger">*</span></label>
                        <div class="file-upload-wrapper">
                            <div class="card card-body upload-area p-3" id="uploadArea">
                                <div class="text-center py-4" id="dragText">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                    <h5>Drag & drop file di sini</h5>
                                    <p class="text-muted">atau</p>
                                    <label for="files" class="btn btn-primary">
                                        <i class="fas fa-folder-open me-2"></i>Pilih File
                                    </label>
                                    <input type="file" class="d-none" id="files" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                </div>
                                <div id="filePreviewContainer" class="d-none">
                                    <h6 class="border-bottom pb-2 mb-3">File yang akan diupload:</h6>
                                    <div id="fileList" class="list-group mb-3"></div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addMoreFiles">
                                            <i class="fas fa-plus me-1"></i> Tambah File Lain
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @error('files')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                            @error('files.*')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">
                            Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX. Maksimal 10MB per file.
                        </div>
                    </div>
                    
                    <!-- Visibility -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">
                                Dokumen dapat diakses publik
                            </label>
                            <div class="form-text">Jika dicentang, dokumen dapat diakses oleh semua pengguna. Jika tidak, hanya admin yang dapat mengakses.</div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-1"></i>
                            Upload Dokumen
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
                    Informasi Upload
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Petunjuk Upload:</h6>
                    <ul class="small">
                        <li>Anda dapat mengupload beberapa file sekaligus</li>
                        <li>Ukuran maksimal per file adalah 10MB</li>
                        <li>Format file yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX</li>
                        <li>Pastikan nama dokumen sudah sesuai</li>
                        <li>Pilih kategori yang tepat untuk memudahkan pencarian</li>
                    </ul>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Kategori Dokumen:</h6>
                    <div class="small">
                        <div class="mb-1"><span class="badge bg-primary">Surat</span> - Surat resmi, surat keputusan, dll</div>
                        <div class="mb-1"><span class="badge bg-success">Laporan</span> - Laporan kegiatan, keuangan, dll</div>
                        <div class="mb-1"><span class="badge bg-danger">Peraturan</span> - Peraturan desa, SK, dll</div>
                        <div class="mb-1"><span class="badge bg-warning text-dark">Pedoman</span> - Pedoman, panduan, dll</div>
                        <div class="mb-1"><span class="badge bg-secondary">Lainnya</span> - Dokumen lain</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('files');
        const fileList = document.getElementById('fileList');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const dragText = document.getElementById('dragText');
        const addMoreFilesBtn = document.getElementById('addMoreFiles');
        const submitBtn = document.getElementById('submitBtn');
        const uploadForm = document.getElementById('uploadForm');
        
        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            uploadArea.classList.add('border-primary');
        }
        
        function unhighlight() {
            uploadArea.classList.remove('border-primary');
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        addMoreFilesBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        function handleFiles(files) {
            if (files.length > 0) {
                dragText.classList.add('d-none');
                filePreviewContainer.classList.remove('d-none');
                
                for (let i = 0; i < files.length; i++) {
                    addFileToList(files[i]);
                }
            }
        }
        
        function addFileToList(file) {
            // Check file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert(`File ${file.name} terlalu besar. Maksimal 10MB per file.`);
                return;
            }
            
            // Check file type
            const validTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 
                               'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                               'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                               'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
            
            if (!validTypes.includes(file.type) && 
                !file.name.endsWith('.pdf') && 
                !file.name.endsWith('.jpg') && 
                !file.name.endsWith('.jpeg') && 
                !file.name.endsWith('.png') && 
                !file.name.endsWith('.doc') && 
                !file.name.endsWith('.docx') && 
                !file.name.endsWith('.xls') && 
                !file.name.endsWith('.xlsx') && 
                !file.name.endsWith('.ppt') && 
                !file.name.endsWith('.pptx')) {
                alert(`File ${file.name} tidak didukung. Format yang didukung: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX.`);
                return;
            }
            
            const fileItem = document.createElement('div');
            fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            
            // Get file icon based on type
            let fileIcon = 'fa-file';
            if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
                fileIcon = 'fa-file-pdf';
            } else if (file.type.startsWith('image/') || file.name.match(/\.(jpg|jpeg|png)$/i)) {
                fileIcon = 'fa-file-image';
            } else if (file.type === 'application/msword' || 
                      file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                      file.name.match(/\.(doc|docx)$/i)) {
                fileIcon = 'fa-file-word';
            } else if (file.type === 'application/vnd.ms-excel' || 
                      file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
                      file.name.match(/\.(xls|xlsx)$/i)) {
                fileIcon = 'fa-file-excel';
            } else if (file.type === 'application/vnd.ms-powerpoint' || 
                      file.type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ||
                      file.name.match(/\.(ppt|pptx)$/i)) {
                fileIcon = 'fa-file-powerpoint';
            }
            
            // Format file size
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <div>
                    <i class="fas ${fileIcon} me-2 text-primary"></i>
                    <span class="file-name">${file.name}</span>
                    <span class="badge bg-light text-dark ms-2">${fileSize}</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger remove-file">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            fileList.appendChild(fileItem);
            
            // Add remove button functionality
            const removeBtn = fileItem.querySelector('.remove-file');
            removeBtn.addEventListener('click', function() {
                fileItem.remove();
                
                // If no files left, show drag text again
                if (fileList.children.length === 0) {
                    dragText.classList.remove('d-none');
                    filePreviewContainer.classList.add('d-none');
                }
            });
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Form submission validation
        uploadForm.addEventListener('submit', function(e) {
            if (fileList.children.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal satu file untuk diupload.');
            }
        });
    });
</script>

<style>
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .upload-area:hover, .upload-area.border-primary {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .file-upload-wrapper {
        position: relative;
    }
</style>
@endpush