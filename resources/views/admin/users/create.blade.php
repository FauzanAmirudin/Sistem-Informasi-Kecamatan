@extends('layouts.admin')

@section('page-title', 'Tambah User Baru')
@section('page-subtitle', 'Tambah pengguna baru')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Form Tambah User
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Foto Profil -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Foto Profil</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="profile_photo" class="form-label">Upload Foto Profil</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                       id="profile_photo" name="profile_photo" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</div>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <img id="preview" src="{{ asset('images/default-avatar.svg') }}" 
                                         alt="Preview" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="form-text">Preview Foto</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Dasar -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Informasi Dasar</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimal 8 karakter</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role dan Akses -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Role dan Akses</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="" selected disabled>Pilih Role</option>
                                    <option value="admin_kecamatan" {{ old('role') == 'admin_kecamatan' ? 'selected' : '' }}>Admin Kecamatan</option>
                                    <option value="admin_desa" {{ old('role') == 'admin_desa' ? 'selected' : '' }}>Admin Desa</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6" id="desaContainer" style="display: none;">
                                <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                                <select class="form-select @error('desa_id') is-invalid @enderror" 
                                        id="desa_id" name="desa_id">
                                    <option value="" selected disabled>Pilih Desa</option>
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
                    </div>
                    
                    <!-- Informasi Kontak -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Informasi Kontak</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh me-1"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading fw-bold"><i class="fas fa-user-shield me-2"></i>Tentang Role</h6>
                    <hr>
                    <p class="mb-0"><strong>Admin Kecamatan</strong> - Memiliki akses penuh ke seluruh sistem dan data semua desa.</p>
                    <p class="mb-0"><strong>Admin Desa</strong> - Hanya dapat mengakses dan mengelola data desa yang ditugaskan.</p>
                </div>
                
                <div class="alert alert-warning">
                    <h6 class="alert-heading fw-bold"><i class="fas fa-key me-2"></i>Keamanan Password</h6>
                    <hr>
                    <p class="mb-0">Pastikan password minimal 8 karakter dan kombinasi huruf, angka, dan simbol untuk keamanan yang lebih baik.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview profile photo
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '{{ asset("images/default-avatar.svg") }}';
        }
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Show/hide desa selection based on role
    document.getElementById('role').addEventListener('change', function() {
        const desaContainer = document.getElementById('desaContainer');
        const desaSelect = document.getElementById('desa_id');
        
        if (this.value === 'admin_desa') {
            desaContainer.style.display = 'block';
            desaSelect.setAttribute('required', 'required');
        } else {
            desaContainer.style.display = 'none';
            desaSelect.removeAttribute('required');
        }
    });
    
    // Initialize desa container visibility based on role selection
    window.addEventListener('DOMContentLoaded', (event) => {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'admin_desa') {
            document.getElementById('desaContainer').style.display = 'block';
            document.getElementById('desa_id').setAttribute('required', 'required');
        }
    });
</script>
@endpush