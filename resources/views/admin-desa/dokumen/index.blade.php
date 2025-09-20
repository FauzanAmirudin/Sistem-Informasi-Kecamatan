@extends('layouts.admin')

@section('page-title', 'Dokumen Desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.dokumen.create') }}" class="btn btn-primary">
        <i class="fas fa-upload me-1"></i>
        Upload Dokumen
    </a>
</div>
@endsection

@section('admin-content')
<div class="card shadow-sm">
    <div class="card-body">
        <!-- Filter dan Pencarian -->
        <form action="{{ route('admin-desa.dokumen.index') }}" method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="surat" {{ request('kategori') == 'surat' ? 'selected' : '' }}>Surat</option>
                        <option value="laporan" {{ request('kategori') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                        <option value="peraturan" {{ request('kategori') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                        <option value="pedoman" {{ request('kategori') == 'pedoman' ? 'selected' : '' }}>Pedoman</option>
                        <option value="lainnya" {{ request('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari Dokumen</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Cari nama dokumen atau deskripsi..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin-desa.dokumen.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- Daftar Dokumen -->
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kategori</th>
                        <th width="25%">Nama Dokumen</th>
                        <th width="10%">Ukuran</th>
                        <th width="10%">Tipe</th>
                        <th width="10%">Preview</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dokumens as $dokumen)
                    <tr>
                        <td>{{ $loop->iteration + $dokumens->firstItem() - 1 }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($dokumen->kategori) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $dokumen->nama_dokumen }}</strong>
                            @if($dokumen->is_public)
                                <span class="badge bg-success ms-1">Publik</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ Str::limit($dokumen->deskripsi, 50) }}</small>
                        </td>
                        <td>{{ $dokumen->file_size_formatted }}</td>
                        <td>
                            @php
                                $extension = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
                            @endphp
                            <span class="badge bg-secondary">{{ strtoupper($extension) }}</span>
                        </td>
                        <td>
                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-image"></i>
                                </a>
                            @elseif($extension == 'pdf')
                                <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="badge bg-secondary">Tidak tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin-desa.dokumen.show', $dokumen) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin-desa.dokumen.download', $dokumen) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('admin-desa.dokumen.edit', $dokumen) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin-desa.dokumen.destroy', $dokumen) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5>Belum ada dokumen</h5>
                                <p class="text-muted">Silakan upload dokumen baru</p>
                                <a href="{{ route('admin-desa.dokumen.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-upload me-1"></i>
                                    Upload Dokumen
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $dokumens->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection