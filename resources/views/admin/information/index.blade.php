@extends('layouts.admin')

@section('page-title', 'Manajemen Informasi')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.information.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Tambah Informasi
    </a>
</div>
@endsection

@section('admin-content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-newspaper me-2 text-primary"></i>
                    Daftar Informasi & Pengumuman
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="published">Dipublikasikan</option>
                        <option value="draft">Draft</option>
                    </select>
                    <select class="form-select form-select-sm" id="kategoriFilter" style="width: auto;">
                        <option value="">Semua Kategori</option>
                        <option value="pengumuman">Pengumuman</option>
                        <option value="informasi">Informasi</option>
                        <option value="berita">Berita</option>
                    </select>
                </div>
            </div>
            <div class="card-body p-0">
                @if($informations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Tanggal Publikasi</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($informations as $index => $information)
                                <tr>
                                    <td>{{ $informations->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $information->judul }}</div>
                                        <small class="text-muted">
                                            {{ Str::limit(strip_tags($information->konten), 100) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($information->kategori) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $information->status_badge }}">
                                            {{ $information->status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $information->creator->name }}</td>
                                    <td>{{ $information->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($information->published_at)
                                            {{ $information->published_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.information.show', $information) }}" 
                                               class="btn btn-outline-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.information.edit', $information) }}" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.information.toggle-status', $information) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-{{ $information->status === 'published' ? 'secondary' : 'success' }}" 
                                                        title="{{ $information->status === 'published' ? 'Ubah ke Draft' : 'Publikasikan' }}">
                                                    <i class="fas fa-{{ $information->status === 'published' ? 'eye-slash' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.information.destroy', $information) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="card-footer bg-white">
                        {{ $informations->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada informasi</h5>
                        <p class="text-muted">Mulai dengan membuat informasi atau pengumuman pertama Anda.</p>
                        <a href="{{ route('admin.information.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Informasi
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const statusFilter = document.getElementById('statusFilter');
    const kategoriFilter = document.getElementById('kategoriFilter');
    
    function applyFilters() {
        const status = statusFilter.value;
        const kategori = kategoriFilter.value;
        const url = new URL(window.location);
        
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        if (kategori) {
            url.searchParams.set('kategori', kategori);
        } else {
            url.searchParams.delete('kategori');
        }
        
        window.location.href = url.toString();
    }
    
    statusFilter.addEventListener('change', applyFilters);
    kategoriFilter.addEventListener('change', applyFilters);
    
    // Set current filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    statusFilter.value = urlParams.get('status') || '';
    kategoriFilter.value = urlParams.get('kategori') || '';
});
</script>
@endpush
