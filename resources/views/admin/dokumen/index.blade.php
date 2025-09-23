@extends('layouts.admin')

@section('page-title', 'Dokumen')
@section('page-subtitle', 'Kelola dokumen dan file sistem')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Upload Dokumen
    </a>
</div>
@endsection

@section('admin-content')
<!-- Filter dan Pencarian -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.dokumen.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori">
                    <option value="">Semua Kategori</option>
                    <option value="surat" {{ request('kategori') == 'surat' ? 'selected' : '' }}>Surat</option>
                    <option value="laporan" {{ request('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                    <option value="peraturan" {{ request('kategori') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                    <option value="pedoman" {{ request('kategori') == 'pedoman' ? 'selected' : '' }}>Pedoman</option>
                    <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="desa_id" class="form-label">Desa</label>
                <select class="form-select" id="desa_id" name="desa_id">
                    <option value="">Semua Desa</option>
                    @foreach(\App\Models\Desa::orderBy('nama_desa')->get() as $desa)
                        <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                            {{ $desa->nama_desa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Dokumen</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nama dokumen atau deskripsi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Dokumen -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-folder me-2"></i>
            Daftar Dokumen
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('errors'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Terdapat error saat upload:</strong>
                <ul class="mb-0 mt-2">
                    @foreach(session('errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kategori</th>
                        <th width="20%">Nama Dokumen</th>
                        <th width="15%">Desa</th>
                        <th width="10%">Ukuran</th>
                        <th width="10%">Tipe</th>
                        <th width="10%">Preview</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dokumens as $index => $dokuman)
                        <tr>
                            <td>{{ $dokumens->firstItem() + $index }}</td>
                            <td>
                                @php
                                    $badgeClass = 'bg-secondary';
                                    if($dokuman->kategori == 'surat') $badgeClass = 'bg-primary';
                                    elseif($dokuman->kategori == 'laporan') $badgeClass = 'bg-success';
                                    elseif($dokuman->kategori == 'peraturan') $badgeClass = 'bg-danger';
                                    elseif($dokuman->kategori == 'pedoman') $badgeClass = 'bg-warning text-dark';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($dokuman->kategori) }}</span>
                            </td>
                            <td>{{ $dokuman->nama_dokumen }}</td>
                            <td>{{ $dokuman->desa->nama_desa ?? 'Tidak ada desa' }}</td>
                            <td>{{ $dokuman->file_size_formatted }}</td>
                            <td>
                                @php
                                    $icon = 'fa-file';
                                    $fileType = $dokuman->file_type;
                                    
                                    if (strpos($fileType, 'pdf') !== false) {
                                        $icon = 'fa-file-pdf';
                                    } elseif (strpos($fileType, 'image') !== false) {
                                        $icon = 'fa-file-image';
                                    } elseif (strpos($fileType, 'word') !== false) {
                                        $icon = 'fa-file-word';
                                    } elseif (strpos($fileType, 'excel') !== false || strpos($fileType, 'spreadsheet') !== false) {
                                        $icon = 'fa-file-excel';
                                    } elseif (strpos($fileType, 'powerpoint') !== false || strpos($fileType, 'presentation') !== false) {
                                        $icon = 'fa-file-powerpoint';
                                    }
                                @endphp
                                <i class="fas {{ $icon }} me-1"></i>
                                {{ strtoupper(pathinfo($dokuman->file_path, PATHINFO_EXTENSION)) }}
                            </td>
                            <td>
                                @if(strpos($dokuman->file_type, 'pdf') !== false)
                                    <a href="{{ route('admin.dokumen.show', $dokuman) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @elseif(strpos($dokuman->file_type, 'image') !== false)
                                    <a href="{{ $dokuman->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.dokumen.download', $dokuman) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('admin.dokumen.edit', $dokuman) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.dokumen.destroy', $dokuman) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada dokumen</h5>
                                    <p class="text-muted">Upload dokumen baru dengan mengklik tombol "Upload Dokumen"</p>
                                    <a href="{{ route('admin.dokumen.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Upload Dokumen
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center py-3 border-top bg-white">
            <nav aria-label="Navigasi halaman">
                {{ $dokumens->withQueryString()->links('vendor.pagination.custom-bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>
@endsection