@extends('layouts.admin')

@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola akun pengguna sistem')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Tambah User
    </a>
</div>
@endsection

@section('admin-content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($users->count()) }}</h4>
                        <small>Total User</small>
                    </div>
                    <i class="fas fa-users fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($users->where('role', 'admin_kecamatan')->count()) }}</h4>
                        <small>Admin Kecamatan</small>
                    </div>
                    <i class="fas fa-user-shield fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($users->where('role', 'admin_desa')->count()) }}</h4>
                        <small>Admin Desa</small>
                    </div>
                    <i class="fas fa-user-cog fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Daftar User
            </h5>
            <div class="form-group mb-0">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari user...">
                    <span class="input-group-text bg-primary text-white">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Desa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                                     class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover;">
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->phone)
                                        <br><small class="text-muted">{{ $user->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin_kecamatan')
                                <span class="badge bg-success">Admin Kecamatan</span>
                            @elseif($user->role === 'admin_desa')
                                <span class="badge bg-info">Admin Desa</span>
                            @endif
                        </td>
                        <td>
                            @if($user->desa)
                                {{ $user->desa->nama_desa }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-status" 
                                       type="checkbox" 
                                       id="status-{{ $user->id }}" 
                                       data-id="{{ $user->id }}" 
                                       data-url="{{ route('admin.users.toggle-status', $user) }}" 
                                       {{ $user->is_active ? 'checked' : '' }}
                                       {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                                <label class="form-check-label" for="status-{{ $user->id }}">
                                    <span class="status-text {{ $user->is_active ? 'text-success' : 'text-danger' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   data-bs-toggle="tooltip" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-sm btn-outline-warning"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary reset-password"
                                        data-id="{{ $user->id }}"
                                        data-url="{{ route('admin.users.reset-password', $user) }}"
                                        data-bs-toggle="tooltip" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Hapus"
                                        onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', 'Hapus user {{ $user->name }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>Belum ada data user.</p>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah User Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage">Apakah Anda yakin ingin menghapus item ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle Status
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.id;
            const url = this.dataset.url;
            const statusText = this.parentNode.querySelector('.status-text');
            
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
                    if (data.is_active) {
                        statusText.textContent = 'Aktif';
                        statusText.classList.remove('text-danger');
                        statusText.classList.add('text-success');
                    } else {
                        statusText.textContent = 'Nonaktif';
                        statusText.classList.remove('text-success');
                        statusText.classList.add('text-danger');
                    }
                    
                    // Show toast notification
                    showToast('success', data.message);
                } else {
                    // Revert toggle if failed
                    this.checked = !this.checked;
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
                showToast('error', 'Terjadi kesalahan saat mengubah status');
            });
        });
    });
    
    // Reset Password
    document.querySelectorAll('.reset-password').forEach(button => {
        button.addEventListener('click', function() {
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
                        showToast('success', data.message);
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Terjadi kesalahan saat mereset password');
                });
            }
        });
    });
    
    // Delete confirmation
    function confirmDelete(url, message) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteMessage').textContent = message;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Toast notification function
    function showToast(type, message) {
        // Check if toastr is available
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
</script>
@endpush