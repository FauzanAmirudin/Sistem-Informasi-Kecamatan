@extends('layouts.admin-desa')

@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Edit profil pengguna')

@section('admin-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Informasi Profil</h3>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin-desa.profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                                    data-url="{{ route('admin-desa.profile.remove-profile-photo') }}">
                                                <i class="fas fa-trash me-1"></i> Hapus Foto
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Informasi Akun</h3>
                </div>
                
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Role:</span>
                            <span class="badge bg-primary">Admin Desa</span>
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
                        <a href="{{ route('admin-desa.profile.reset-password') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-1"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection('admin-content')

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
</script>
@endpush