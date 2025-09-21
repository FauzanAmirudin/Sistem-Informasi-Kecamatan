@extends('layouts.admin')

@section('page-title', 'Edit User: ' . $user->name)

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info">
        <i class="fas fa-eye me-1"></i>
        Lihat Detail
    </a>
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
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit me-2"></i>
                    Form Edit User
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                                    <img id="preview" src="{{ $user->profile_photo_url }}" 
                                         alt="Preview" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    <div class="form-text">Preview Foto</div>
                                    @if($user->profile_photo)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-photo"
                                                data-url="{{ route('admin.users.remove-profile-photo', $user) }}">
                                            <i class="fas fa-trash me-1"></i> Hapus Foto
                                        </button>
                                    </div>
                                    @endif
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
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    <option value="" disabled>Pilih Role</option>
                                    <option value="admin_kecamatan" {{ old('role', $user->role) == 'admin_kecamatan' ? 'selected' : '' }}>Admin Kecamatan</option>
                                    <option value="admin_desa" {{ old('role', $user->role) == 'admin_desa' ? 'selected' : '' }}>Admin Desa</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6" id="desaContainer" style="display: {{ old('role', $user->role) == 'admin_desa' ? 'block' : 'none' }}">
                                <label for="desa_id" class="form-label">Desa <span class="text-danger">*</span></label>
                                <select class="form-select @error('desa_id') is-invalid @enderror" 
                                        id="desa_id" name="desa_id" {{ old('role', $user->role) == 'admin_desa' ? 'required' : '' }}>
                                    <option value="" disabled>Pilih Desa</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ old('desa_id', $user->desa_id) == $desa->id ? 'selected' : '' }}>
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
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i>
                            Perbarui
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
                    Informasi User
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                         class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status:</span>
                        <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Role:</span>
                        <span class="badge {{ $user->role === 'admin_kecamatan' ? 'bg-success' : 'bg-info' }}">
                            {{ $user->role === 'admin_kecamatan' ? 'Admin Kecamatan' : 'Admin Desa' }}
                        </span>
                    </div>
                </div>
                
                @if($user->desa)
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Desa:</span>
                        <span>{{ $user->desa->nama_desa }}</span>
                    </div>
                </div>
                @endif
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Terdaftar:</span>
                        <span>{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Terakhir Diperbarui:</span>
                        <span>{{ $user->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    @if($user->id !== Auth::id())
                    <button type="button" class="btn btn-outline-secondary reset-password"
                            data-id="{{ $user->id }}"
                            data-url="{{ route('admin.users.reset-password', $user) }}">
                        <i class="fas fa-key me-1"></i>
                        Reset Password
                    </button>
                    @endif
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
        }
    });

    // Remove profile photo
    const removePhotoBtn = document.querySelector('.remove-photo');
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', function() {
            const url = this.dataset.url;
            
            if (confirm('Apakah Anda yakin ingin menghapus foto profil ini?')) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('preview').src = '{{ asset("images/default-avatar.svg") }}';
                        this.style.display = 'none';
                        alert(data.message);
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus foto');
                });
            }
        });
    }

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
    
    // Reset Password
    const resetPasswordBtn = document.querySelector('.reset-password');
    if (resetPasswordBtn) {
        resetPasswordBtn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const url = this.dataset.url;
            
            if (confirm('Apakah Anda yakin ingin mereset password user ini?')) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mereset password');
                });
            }
        });
    }
</script>
@endpush