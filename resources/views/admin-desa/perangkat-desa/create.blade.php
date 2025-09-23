@extends('layouts.admin-desa')

@section('page-title', 'Tambah Perangkat Desa')
@section('page-subtitle', 'Tambah data perangkat desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Form Tambah Perangkat Desa
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin-desa.perangkat-desa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <!-- Data Dasar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Data Dasar</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                       id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                       id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" required>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Nomor Induk Kependudukan 16 digit</small>
                            </div>
                        </div>
                        
                        <input type="hidden" name="desa_id" value="{{ $desa->id }}">
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Kelahiran -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Data Kelahiran</h5>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                       id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                       id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="usia" class="form-label">Usia</label>
                                <input type="text" class="form-control" id="usia" readonly>
                                <small class="text-muted">Otomatis dihitung dari tanggal lahir</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Tambahan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Data Tambahan</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select class="form-select @error('pendidikan_terakhir') is-invalid @enderror" 
                                        id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                    <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
                                    <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ old('pendidikan_terakhir') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D1" {{ old('pendidikan_terakhir') == 'D1' ? 'selected' : '' }}>D1</option>
                                    <option value="D2" {{ old('pendidikan_terakhir') == 'D2' ? 'selected' : '' }}>D2</option>
                                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="D4" {{ old('pendidikan_terakhir') == 'D4' ? 'selected' : '' }}>D4</option>
                                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('pendidikan_terakhir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="no_telepon" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" name="alamat" rows="3" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Tugas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Data Tugas</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_mulai_tugas" class="form-label">Tanggal Mulai Tugas <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai_tugas') is-invalid @enderror" 
                                       id="tanggal_mulai_tugas" name="tanggal_mulai_tugas" value="{{ old('tanggal_mulai_tugas') }}" required>
                                @error('tanggal_mulai_tugas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_akhir_tugas" class="form-label">Tanggal Akhir Tugas</label>
                                <input type="date" class="form-control @error('tanggal_akhir_tugas') is-invalid @enderror" 
                                       id="tanggal_akhir_tugas" name="tanggal_akhir_tugas" value="{{ old('tanggal_akhir_tugas') }}">
                                @error('tanggal_akhir_tugas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kosongkan jika masa tugas belum ditentukan</small>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="jobdesk" class="form-label">Job Description / Tugas Pokok</label>
                                <textarea class="form-control @error('jobdesk') is-invalid @enderror" 
                                          id="jobdesk" name="jobdesk" rows="3">{{ old('jobdesk') }}</textarea>
                                @error('jobdesk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Dokumen & Status -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2">Dokumen & Status</h5>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sk_pengangkatan" class="form-label">SK Pengangkatan</label>
                                <input type="file" class="form-control @error('sk_pengangkatan') is-invalid @enderror" 
                                       id="sk_pengangkatan" name="sk_pengangkatan">
                                @error('sk_pengangkatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Maks. 2MB)</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="" selected disabled>Pilih Status</option>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto calculate age
    function calculateAge() {
        const birthDate = document.getElementById('tanggal_lahir').value;
        if (birthDate) {
            const today = new Date();
            const dob = new Date(birthDate);
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            document.getElementById('usia').value = age + ' tahun';
        } else {
            document.getElementById('usia').value = '';
        }
    }
    
    // Auto format NIK
    function formatNIK() {
        let nik = document.getElementById('nik').value;
        nik = nik.replace(/\D/g, ''); // Remove non-digits
        if (nik.length > 16) {
            nik = nik.substring(0, 16);
        }
        document.getElementById('nik').value = nik;
    }
    
    // Validate end date
    function validateEndDate() {
        const startDate = document.getElementById('tanggal_mulai_tugas').value;
        const endDate = document.getElementById('tanggal_akhir_tugas').value;
        
        if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
            alert('Tanggal akhir tugas harus setelah tanggal mulai tugas!');
            document.getElementById('tanggal_akhir_tugas').value = '';
        }
    }
    
    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Initial calculation
        calculateAge();
        
        // Add event listeners
        document.getElementById('tanggal_lahir').addEventListener('change', calculateAge);
        document.getElementById('nik').addEventListener('input', formatNIK);
        document.getElementById('tanggal_mulai_tugas').addEventListener('change', validateEndDate);
        document.getElementById('tanggal_akhir_tugas').addEventListener('change', validateEndDate);
    });
</script>
@endpush