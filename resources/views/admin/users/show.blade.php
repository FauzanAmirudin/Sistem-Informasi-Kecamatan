@extends('layouts.admin')

@section('page-title', 'Detail User: ' . $user->name)

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>
        Edit
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <!-- Informasi User -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    Informasi User
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                         class="rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} mb-2">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </div>
                    <div class="badge {{ $user->role === 'admin_kecamatan' ? 'bg-success' : 'bg-info' }}">
                        {{ $user->role === 'admin_kecamatan' ? 'Admin Kecamatan' : 'Admin Desa' }}
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Informasi Kontak</h6>
                    <div class="mb-2">
                        <small class="text-muted">Telepon:</small>
                        <div>{{ $user->phone ?: 'Tidak ada' }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Alamat:</small>
                        <div>{{ $user->address ?: 'Tidak ada' }}</div>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6 class="fw-bold">Informasi Akun</h6>
                    @if($user->desa)
                    <div class="mb-2">
                        <small class="text-muted">Desa:</small>
                        <div>{{ $user->desa->nama_desa }}</div>
                    </div>
                    @endif
                    <div class="mb-2">
                        <small class="text-muted">Terdaftar pada:</small>
                        <div>{{ $user->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Terakhir diperbarui:</small>
                        <div>{{ $user->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-grid gap-2">
                    @if($user->id !== Auth::id())
                    <button type="button" class="btn btn-outline-primary toggle-status"
                            data-id="{{ $user->id }}"
                            data-status="{{ $user->is_active ? 1 : 0 }}"
                            data-url="{{ route('admin.users.toggle-status', $user) }}">
                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }} me-1"></i>
                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    
                    <button type="button" class="btn btn-outline-secondary reset-password"
                            data-id="{{ $user->id }}"
                            data-url="{{ route('admin.users.reset-password', $user) }}">
                        <i class="fas fa-key me-1"></i>
                        Reset Password
                    </button>
                    
                    <button type="button" class="btn btn-outline-danger delete-user"
                            data-id="{{ $user->id }}"
                            data-url="{{ route('admin.users.destroy', $user) }}">
                        <i class="fas fa-trash me-1"></i>
                        Hapus User
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity Log -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Aktivitas
                </h5>
            </div>
            <div class="card-body">
                @if(count($activities) > 0)
                <div class="timeline">
                    @foreach($activities as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $activity->log_name == 'login' ? 'success' : 'primary' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">{{ $activity->description }}</h6>
                                <small class="text-muted">{{ $activity->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <div class="text-muted">
                                @if($activity->log_name == 'login')
                                    <i class="fas fa-sign-in-alt me-1"></i> Login ke sistem
                                @elseif($activity->log_name == 'update')
                                    <i class="fas fa-edit me-1"></i> Memperbarui data
                                @elseif($activity->log_name == 'create')
                                    <i class="fas fa-plus-circle me-1"></i> Membuat data baru
                                @elseif($activity->log_name == 'delete')
                                    <i class="fas fa-trash me-1"></i> Menghapus data
                                @else
                                    <i class="fas fa-info-circle me-1"></i> {{ $activity->log_name }}
                                @endif
                            </div>
                            @if($activity->properties && count($activity->properties) > 0)
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#activity-{{ $activity->id }}" aria-expanded="false">
                                    Detail Perubahan
                                </button>
                                <div class="collapse mt-2" id="activity-{{ $activity->id }}">
                                    <div class="card card-body bg-light">
                                        <pre class="mb-0">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="mb-0">Belum ada aktivitas yang tercatat.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-top: 5px;
    }
    
    .timeline-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle Status
    const toggleStatusBtn = document.querySelector('.toggle-status');
    if (toggleStatusBtn) {
        toggleStatusBtn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const currentStatus = parseInt(this.dataset.status);
            const url = this.dataset.url;
            const statusText = currentStatus ? 'nonaktifkan' : 'aktifkan';
            
            if (confirm(`Apakah Anda yakin ingin ${statusText} user ini?`)) {
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
                        window.location.reload();
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah status user');
                });
            }
        });
    }
    
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
    
    // Delete User
    const deleteUserBtn = document.querySelector('.delete-user');
    if (deleteUserBtn) {
        deleteUserBtn.addEventListener('click', function() {
            const userId = this.dataset.id;
            const url = this.dataset.url;
            
            if (confirm('Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("admin.users.index") }}';
                    } else {
                        alert('Terjadi kesalahan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus user');
                });
            }
        });
    }
</script>
@endpush