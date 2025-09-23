@extends('layouts.admin')

@section('page-title', 'Data Desa')
@section('page-subtitle', 'Kelola data desa di kecamatan')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.desa.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Tambah Desa
    </a>
    <a href="{{ route('admin.monitoring') }}" class="btn btn-outline-info">
        <i class="fas fa-map-marked-alt me-1"></i>
        Lihat Peta
    </a>
</div>
@endsection

@section('admin-content')
<!-- Filter dan Search -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.desa.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari nama desa atau kepala desa..."
                                   autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status Update</label>
                            <select class="form-select" name="update_status">
                                <option value="">Semua</option>
                                <option value="hijau" {{ request('update_status') == 'hijau' ? 'selected' : '' }}>Update Terbaru</option>
                                <option value="kuning" {{ request('update_status') == 'kuning' ? 'selected' : '' }}>Perlu Update</option>
                                <option value="merah" {{ request('update_status') == 'merah' ? 'selected' : '' }}>Butuh Perhatian</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Cari Data">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('admin.desa.index') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Reset Filter">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light shadow-sm">
            <div class="card-body text-center">
                <h5 class="card-title">
                    <i class="fas fa-home text-primary me-2"></i>
                    @if(request()->hasAny(['search', 'status', 'update_status']))
                        Hasil Pencarian
                    @else
                        Total Desa
                    @endif
                </h5>
                <h2 class="text-primary mb-0">{{ $desas->total() }}</h2>
                @if(request()->hasAny(['search', 'status', 'update_status']))
                    <small class="text-muted">
                        dari {{ App\Models\Desa::count() }} total desa
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tabel Data Desa -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2 text-secondary"></i>
                Daftar Desa
            </h5>
            @if(request()->hasAny(['search', 'status', 'update_status']))
                <div class="d-flex align-items-center">
                    <span class="badge bg-info me-2">
                        <i class="fas fa-filter me-1"></i>
                        Filter Aktif
                    </span>
                    <a href="{{ route('admin.desa.index') }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Hapus Semua Filter">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if(request()->hasAny(['search', 'status', 'update_status']))
            <div class="alert alert-info alert-dismissible fade show m-3 mb-0" role="alert">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Filter Aktif:</strong>
                        @if(request('search'))
                            <span class="badge bg-primary me-1">Pencarian: "{{ request('search') }}"</span>
                        @endif
                        @if(request('status'))
                            <span class="badge bg-success me-1">Status: {{ ucfirst(request('status')) }}</span>
                        @endif
                        @if(request('update_status'))
                            @php
                                $updateStatusText = request('update_status') == 'hijau' ? 'Update Terbaru' : 
                                                   (request('update_status') == 'kuning' ? 'Perlu Update' : 'Butuh Perhatian');
                            @endphp
                            <span class="badge bg-warning me-1">Status Update: {{ $updateStatusText }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.desa.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Hapus Filter
                    </a>
                </div>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Desa</th>
                        <th>Kode Desa</th>
                        <th>Kepala Desa</th>
                        <th>Penduduk</th>
                        <th>Perangkat</th>
                        <th>Status Update</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($desas as $index => $desa)
                    <tr>
                        <td>{{ ($desas->currentPage() - 1) * $desas->perPage() + $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $desa->nama_desa }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ Str::limit($desa->alamat, 30) }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $desa->kode_desa }}</span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $desa->kepala_desa }}</strong>
                                @if($desa->sk_kepala_desa)
                                    <br><small class="text-success">
                                        <i class="fas fa-file-check me-1"></i>
                                        Ada SK
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ number_format($desa->penduduks_count) }} jiwa
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">
                                {{ $desa->perangkat_desas_count }} orang
                            </span>
                        </td>
                        <td>
                            @php
                                $statusUpdate = $desa->status_update;
                                $statusClass = $statusUpdate == 'hijau' ? 'success' : ($statusUpdate == 'kuning' ? 'warning' : 'danger');
                                $statusText = $statusUpdate == 'hijau' ? 'Terbaru' : ($statusUpdate == 'kuning' ? 'Perlu Update' : 'Butuh Perhatian');
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                <i class="fas fa-circle me-1"></i>
                                {{ $statusText }}
                            </span>
                            <br>
                            <small class="text-muted">
                                @if($desa->last_updated_at)
                                    {{ $desa->last_updated_at->diffForHumans() }}
                                @else
                                    Belum pernah update
                                @endif
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $desa->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($desa->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.desa.show', $desa) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   data-bs-toggle="tooltip" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.desa.edit', $desa) }}" 
                                   class="btn btn-sm btn-outline-warning"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Hapus"
                                        onclick="confirmDelete('{{ route('admin.desa.destroy', $desa) }}', 'Hapus desa {{ $desa->nama_desa }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-home fa-3x mb-3"></i>
                                <p>Belum ada data desa.</p>
                                <a href="{{ route('admin.desa.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Desa Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center py-3 border-top bg-white">
        <nav aria-label="Navigasi halaman">
            {{ $desas->links('vendor.pagination.custom-bootstrap-5') }}
        </nav>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, message) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Initialize tooltips and enhance UX
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-focus on search field if empty
    const searchField = document.querySelector('input[name="search"]');
    if (searchField && !searchField.value) {
        searchField.focus();
    }
    
    // Add keyboard shortcut for search (Ctrl+F)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            searchField.focus();
        }
    });
    
    // Add Enter key support for search
    if (searchField) {
        searchField.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.querySelector('form').submit();
            }
        });
    }
});
</script>
@endpush