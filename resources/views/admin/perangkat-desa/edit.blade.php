@extends('layouts.admin')

@section('page-title', 'Edit Perangkat: ' . $perangkatDesa->nama_lengkap)
@section('page-subtitle', 'Edit data perangkat desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.perangkat-desa.show', $perangkatDesa) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
    <a href="{{ route('admin.perangkat-desa.riwayat', $perangkatDesa) }}" class="btn btn-secondary">
        <i class="fas fa-history me-1"></i>
        Riwayat
    </a>
    <a href="{{ route('admin.perangkat-desa.index') }}" class="btn btn-secondary">
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
                    <i class="fas fa-user-edit me-2"></i>
                    Form Edit Perangkat Desa
                </h5>
            </div>
            <div class="card-body">
                <!-- Debug: ID Perangkat Desa = {{ $perangkatDesa->id }} -->
                <form id="edit-perangkat-form" action="{{ route('admin.perangkat-desa.update', $perangkatDesa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_method" value="PUT">
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    
                    <!-- Alasan Update -->
                    <div class="mb-3 border p-3 rounded bg-light" id="alasan_update_container">
                        <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Alasan Perubahan</h5>
                        <div class="form-group">
                            <label for="update_reason" class="form-label fw-bold">Alasan Perubahan Data <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('update_reason') is-invalid @enderror" 
                                    id="update_reason" name="update_reason" rows="4" 
                                    placeholder="Jelaskan alasan perubahan data ini..." required>{{ old('update_reason', $perangkatDesa->update_reason) }}</textarea>
                            <small class="text-muted">Wajib diisi untuk menjelaskan alasan perubahan data</small>
                            @error('update_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Dasar -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $perangkatDesa->nama_lengkap) }}" 
                                   placeholder="Nama lengkap perangkat desa">
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                   id="jabatan" name="jabatan" value="{{ old('jabatan', $perangkatDesa->jabatan) }}" 
                                   placeholder="Contoh: Kepala Desa, Sekretaris Desa">
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                   id="nik" name="nik" value="{{ old('nik', $perangkatDesa->nik) }}" 
                                   placeholder="16 digit NIK" maxlength="16">
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                            <select class="form-select @error('desa_id') is-invalid @enderror" id="desa_id" name="desa_id">
                                <option value="">Pilih Desa...</option>
                                @foreach($desas as $desa)
                                    <option value="{{ $desa->id }}" {{ old('desa_id', $perangkatDesa->desa_id) == $desa->id ? 'selected' : '' }}>
                                        {{ $desa->nama_desa }}
                                    </option>
                                @endforeach
                            </select>
                            @error('desa_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('jenis_kelamin', $perangkatDesa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $perangkatDesa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                   id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $perangkatDesa->tempat_lahir) }}" 
                                   placeholder="Kota/Kabupaten lahir">
                            @error('tempat_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $perangkatDesa->tanggal_lahir->format('Y-m-d')) }}">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Usia</label>
                            <input type="text" class="form-control" id="usia_display" readonly value="{{ \Carbon\Carbon::parse($perangkatDesa->tanggal_lahir)->age }} tahun">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('pendidikan_terakhir') is-invalid @enderror" 
                                   id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $perangkatDesa->pendidikan_terakhir) }}" 
                                   placeholder="Contoh: S1 Administrasi Negara">
                            @error('pendidikan_terakhir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="no_telepon" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                   id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $perangkatDesa->no_telepon) }}" 
                                   placeholder="Nomor telepon aktif">
                            @error('no_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                  id="alamat" name="alamat" rows="2" 
                                  placeholder="Alamat lengkap tempat tinggal">{{ old('alamat', $perangkatDesa->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Masa Tugas -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_mulai_tugas" class="form-label">Tanggal Mulai Tugas <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai_tugas') is-invalid @enderror" 
                                   id="tanggal_mulai_tugas" name="tanggal_mulai_tugas" 
                                   value="{{ old('tanggal_mulai_tugas', $perangkatDesa->tanggal_mulai_tugas->format('Y-m-d')) }}">
                            @error('tanggal_mulai_tugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_akhir_tugas" class="form-label">Tanggal Akhir Tugas</label>
                            <input type="date" class="form-control @error('tanggal_akhir_tugas') is-invalid @enderror" 
                                   id="tanggal_akhir_tugas" name="tanggal_akhir_tugas" 
                                   value="{{ old('tanggal_akhir_tugas', $perangkatDesa->tanggal_akhir_tugas ? $perangkatDesa->tanggal_akhir_tugas->format('Y-m-d') : '') }}">
                            <div class="form-text">Kosongkan jika masih aktif</div>
                            @error('tanggal_akhir_tugas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="mb-3">
                        <label for="jobdesk" class="form-label">Job Description / Tugas Pokok</label>
                        <textarea class="form-control @error('jobdesk') is-invalid @enderror" 
                                  id="jobdesk" name="jobdesk" rows="3" 
                                  placeholder="Deskripsi tugas dan tanggung jawab...">{{ old('jobdesk', $perangkatDesa->jobdesk) }}</textarea>
                        @error('jobdesk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload SK -->
                    <div class="mb-3">
                        <label for="sk_pengangkatan" class="form-label">SK Pengangkatan</label>
                        @if($perangkatDesa->sk_pengangkatan)
                            <div class="mb-2">
                                <a href="{{ Storage::url($perangkatDesa->sk_pengangkatan) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye me-1"></i>
                                    Lihat File Saat Ini
                                </a>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('sk_pengangkatan') is-invalid @enderror" 
                               id="sk_pengangkatan" name="sk_pengangkatan" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Format: PDF, JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</div>
                        @error('sk_pengangkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="aktif" {{ old('status', $perangkatDesa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status', $perangkatDesa->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.perangkat-desa.show', $perangkatDesa) }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" id="btn-update-perangkat" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>
                            Update Data
                        </button>
                    </div>
                    
                    <!-- Debug info -->
                    <div class="mt-3 d-none" id="debug-info">
                        <div class="alert alert-info">
                            <h6>Debug Info:</h6>
                            <div id="debug-output"></div>
                        </div>
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
                    Informasi Update
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small>
                        <strong>Dibuat:</strong><br>
                        {{ $perangkatDesa->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="mb-3">
                    <small>
                        <strong>Terakhir Diubah:</strong><br>
                        {{ $perangkatDesa->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                @if($perangkatDesa->updatedBy)
                <div class="mb-3">
                    <small>
                        <strong>Diubah Oleh:</strong><br>
                        {{ $perangkatDesa->updatedBy->name }}
                    </small>
                </div>
                @endif
                <div class="mb-3">
                    <small>
                        <strong>Total Riwayat:</strong><br>
                        <span class="badge bg-secondary">{{ $perangkatDesa->riwayat->count() }} perubahan</span>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    /* Ensure alasan update container is always visible */
    #alasan_update_container {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 1000 !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug form submission
    const form = document.getElementById('edit-perangkat-form');
    const debugInfo = document.getElementById('debug-info');
    const debugOutput = document.getElementById('debug-output');
    const submitBtn = document.getElementById('btn-update-perangkat');
    const manualSubmitBtn = document.getElementById('btn-manual-submit');
    
    console.log('Form detected:', form);
    
    // Ensure alasan update container is visible on page load
    window.setTimeout(function() {
        ensureAlasanUpdateVisible();
        console.log('Ensuring alasan update visibility on page load');
    }, 100);
    
    function logDebug(message) {
        console.log(message);
        if (debugOutput) {
            const logEntry = document.createElement('div');
            logEntry.textContent = message;
            debugOutput.appendChild(logEntry);
        }
    }
    
    // Ensure alasan update container is visible
    function ensureAlasanUpdateVisible() {
        const alasanContainer = document.getElementById('alasan_update_container');
        if (alasanContainer) {
            alasanContainer.style.display = 'block';
            alasanContainer.style.visibility = 'visible';
            alasanContainer.style.opacity = '1';
            logDebug('Ensuring alasan update container is visible');
        }
    }
    
    // Call this function on page load
    ensureAlasanUpdateVisible();
    
    // Set interval to ensure alasan update container remains visible
    setInterval(function() {
        ensureAlasanUpdateVisible();
    }, 1000); // Check every second
    
    // Function to manually submit form
    function manualSubmitForm(event) {
        // Prevent default form submission if event is provided
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Ensure alasan update container is visible
        ensureAlasanUpdateVisible();
        
        logDebug('Manual form submission initiated');
        
        // Show debug info
        debugInfo.classList.remove('d-none');
        
        // Validate required fields
        const requiredFields = [
            { name: 'update_reason', label: 'Alasan perubahan data', id: 'update_reason' },
            { name: 'nama_lengkap', label: 'Nama lengkap', id: 'nama_lengkap' },
            { name: 'jabatan', label: 'Jabatan', id: 'jabatan' },
            { name: 'nik', label: 'NIK', id: 'nik' },
            { name: 'jenis_kelamin', label: 'Jenis kelamin', id: 'jenis_kelamin' },
            { name: 'tempat_lahir', label: 'Tempat lahir', id: 'tempat_lahir' },
            { name: 'tanggal_lahir', label: 'Tanggal lahir', id: 'tanggal_lahir' },
            { name: 'pendidikan_terakhir', label: 'Pendidikan terakhir', id: 'pendidikan_terakhir' },
            { name: 'no_telepon', label: 'Nomor telepon', id: 'no_telepon' },
            { name: 'alamat', label: 'Alamat', id: 'alamat' },
            { name: 'tanggal_mulai_tugas', label: 'Tanggal mulai tugas', id: 'tanggal_mulai_tugas' },
            { name: 'status', label: 'Status', id: 'status' }
        ];
        
        // Validasi alasan perubahan terlebih dahulu
        const updateReasonField = document.getElementById('update_reason');
        if (!updateReasonField || !updateReasonField.value.trim()) {
            logDebug('ERROR: update_reason is required');
            alert('Alasan perubahan data wajib diisi!');
            if (updateReasonField) {
                updateReasonField.focus();
                // Scroll ke elemen alasan perubahan
                updateReasonField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }
        
        // Validasi field lainnya
        for (const field of requiredFields) {
            if (field.name === 'update_reason') continue; // Sudah divalidasi di atas
            
            // Coba cari elemen dengan ID terlebih dahulu, jika tidak ada gunakan name
            let element = null;
            if (field.id) {
                element = document.getElementById(field.id);
            }
            
            if (!element) {
                element = document.querySelector(`[name="${field.name}"]`);
            }
            
            if (!element || !element.value.trim()) {
                logDebug(`ERROR: ${field.name} is required`);
                alert(`${field.label} wajib diisi!`);
                if (element) {
                    element.focus();
                    // Scroll ke elemen yang error
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return false;
            }
            
            // Validate NIK format
            if (field.name === 'nik') {
                const nikValue = element.value.trim();
                if (nikValue.length !== 16 || !/^\d+$/.test(nikValue)) {
                    logDebug('ERROR: NIK must be 16 digits');
                    alert('NIK harus terdiri dari 16 digit angka!');
                    element.focus();
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
            
            // Validate date fields
            if (field.name === 'tanggal_lahir' || field.name === 'tanggal_mulai_tugas' || field.name === 'tanggal_akhir_tugas') {
                if (element.value) {
                    const dateValue = new Date(element.value);
                    if (isNaN(dateValue.getTime())) {
                        logDebug(`ERROR: ${field.name} is not a valid date`);
                        alert(`${field.label} tidak valid!`);
                        element.focus();
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                }
            }
        }
        
        // Validate tanggal_akhir_tugas is after tanggal_mulai_tugas
        const tanggalMulaiTugas = document.querySelector('[name="tanggal_mulai_tugas"]');
        const tanggalAkhirTugas = document.querySelector('[name="tanggal_akhir_tugas"]');
        
        if (tanggalMulaiTugas && tanggalAkhirTugas && tanggalMulaiTugas.value && tanggalAkhirTugas.value) {
            const startDate = new Date(tanggalMulaiTugas.value);
            const endDate = new Date(tanggalAkhirTugas.value);
            
            if (endDate <= startDate) {
                logDebug('ERROR: tanggal_akhir_tugas must be after tanggal_mulai_tugas');
                alert('Tanggal akhir tugas harus setelah tanggal mulai tugas!');
                tanggalAkhirTugas.focus();
                tanggalAkhirTugas.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        }
        
        // Semua validasi berhasil, tampilkan informasi debug
        logDebug('All validations passed, submitting form');
        
        try {
            // Pastikan form memiliki method dan action yang benar
            if (!form.method) form.method = 'POST';
            if (!form.action) form.action = window.location.href;
            
            // Pastikan _method hidden field ada untuk Laravel
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            }
            
            // Pastikan enctype sudah benar untuk file upload
            form.enctype = 'multipart/form-data';
            
            // Submit form secara normal
            logDebug('Submitting form with action: ' + form.action + ' and method: ' + form.method);
            form.submit();
            return true;
        } catch (error) {
            logDebug('ERROR during manual submission: ' + error.message);
            console.error('Manual submission error:', error);
            alert('Terjadi kesalahan saat mengirim form: ' + error.message);
            return false;
        }
        }
    }
    
    if (form) {
        logDebug('Form properties: ' + 
                 'action=' + form.action + ', ' +
                 'method=' + form.method + ', ' +
                 'enctype=' + form.enctype);
        
        // Ensure form submission works with both approaches
        form.addEventListener('submit', function(e) {
            // Ensure alasan update container is visible
            ensureAlasanUpdateVisible();
            
            // Prevent default initially
            e.preventDefault();
            e.stopPropagation();
            logDebug('Form submit event triggered');
            
            // Run validation and submit manually if valid
            if (manualSubmitForm(e) === true) {
                logDebug('Validation passed, submitting form');
                // Form will be submitted inside manualSubmitForm
            } else {
                logDebug('Validation failed, preventing form submission');
                ensureAlasanUpdateVisible(); // Ensure it's still visible after validation fails
                return false;
            }
        });
        
        // Ensure submit button works with direct form submission
        if (submitBtn) {
            logDebug('Submit button found: ' + submitBtn.textContent.trim());
            
            submitBtn.addEventListener('click', function(e) {
                logDebug('Submit button clicked');
                
                // If normal submission doesn't work, try programmatic submission
                setTimeout(function() {
                    // This timeout gives the normal form submission a chance to work first
                    // Only show debug info if we're still on the page after 500ms
                    debugInfo.classList.remove('d-none');
                }, 500);
            });
            
            // Add alternative submission method
            submitBtn.addEventListener('dblclick', function(e) {
                e.preventDefault();
                e.stopPropagation();
                ensureAlasanUpdateVisible(); // Ensure alasan update container is visible
                logDebug('Double-click detected - trying alternative submission');
                manualSubmitForm(e);
            });
            
            // Also ensure visibility on normal click
            submitBtn.addEventListener('click', function() {
                ensureAlasanUpdateVisible();
                logDebug('Submit button clicked - ensuring alasan update visibility');
            });
        } else {
            console.error('Submit button not found!');
            logDebug('ERROR: Submit button not found!');
        }
        
        // Add manual submit button handler
        if (manualSubmitBtn) {
            logDebug('Manual submit button found');
            
            manualSubmitBtn.addEventListener('click', function(e) {
                e.preventDefault();
                ensureAlasanUpdateVisible(); // Ensure alasan update container is visible
                logDebug('Manual submit button clicked');
                manualSubmitForm(e);
            });
        } else {
            logDebug('WARNING: Manual submit button not found');
        }
    }
    
    // Auto calculate age
    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    const usiaDisplay = document.getElementById('usia_display');
    
    if (tanggalLahirInput && usiaDisplay) {
        tanggalLahirInput.addEventListener('change', function() {
            if (this.value) {
                const birthDate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                
                usiaDisplay.value = age + ' tahun';
            }
        });
    }

    // Auto format NIK
    const nikInput = document.getElementById('nik');
    if (nikInput) {
        nikInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }
        });
    }

    // Validate end date
    const tanggalMulaiInput = document.getElementById('tanggal_mulai_tugas');
    const tanggalAkhirInput = document.getElementById('tanggal_akhir_tugas');
    
    if (tanggalMulaiInput && tanggalAkhirInput) {
        tanggalMulaiInput.addEventListener('change', function() {
            tanggalAkhirInput.min = this.value;
        });
    }
});
</script>
@endpush