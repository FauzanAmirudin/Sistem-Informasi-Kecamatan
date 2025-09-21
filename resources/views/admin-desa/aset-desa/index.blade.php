@extends('layouts.admin-desa')

@section('page-title', 'Aset Desa')

@section('page-actions')
<div class="btn-group d-flex flex-wrap" role="group">
    <a href="{{ route('admin-desa.aset-desa.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i>
        <span class="d-none d-md-inline">Tambah Aset</span>
        <span class="d-inline d-md-none">Tambah</span>
    </a>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-download me-1"></i>
            <span class="d-none d-md-inline">Export</span>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin-desa.aset-desa.export.excel', request()->query()) }}">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a></li>
            <li><a class="dropdown-item" href="{{ route('admin-desa.aset-desa.export.pdf', request()->query()) }}">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-upload me-1"></i>
            <span class="d-none d-md-inline">Import</span>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin-desa.aset-desa.download-template') }}">
                <i class="fas fa-download me-2"></i>Download Template
            </a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-2"></i>Import Data
            </a></li>
        </ul>
    </div>
</div>
@endsection

@section('admin-content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Aset</h6>
                        <h2 class="mt-2 mb-0">{{ $totalAset }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-building fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Kondisi Baik</h6>
                        <h2 class="mt-2 mb-0">{{ $asetBaik }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Rusak Ringan</h6>
                        <h2 class="mt-2 mb-0">{{ $asetRusakRingan }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card bg-danger text-white shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Rusak Berat</h6>
                        <h2 class="mt-2 mb-0">{{ $asetRusakBerat }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-times-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body p-3 p-md-4">
        <form action="{{ route('admin-desa.aset-desa.index') }}" method="GET" class="row g-3">
            <div class="col-md-4 col-sm-12">
                <label for="search" class="form-label mb-2">Cari</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nama / Lokasi / Deskripsi" value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="kategori_aset" class="form-label mb-2">Kategori</label>
                <select class="form-select" id="kategori_aset" name="kategori_aset">
                    <option value="">Semua Kategori</option>
                    <option value="tanah" {{ request('kategori_aset') == 'tanah' ? 'selected' : '' }}>Tanah</option>
                    <option value="bangunan" {{ request('kategori_aset') == 'bangunan' ? 'selected' : '' }}>Bangunan</option>
                    <option value="inventaris" {{ request('kategori_aset') == 'inventaris' ? 'selected' : '' }}>Inventaris</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="kondisi" class="form-label mb-2">Kondisi</label>
                <select class="form-select" id="kondisi" name="kondisi">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex align-items-end">
                <div class="d-grid gap-2 w-100">
                    <button type="submit" class="btn btn-primary py-2">
                        <i class="fas fa-filter me-1"></i> <span class="d-none d-md-inline">Filter</span>
                    </button>
                    <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-outline-secondary py-2">
                        <i class="fas fa-sync-alt me-1"></i> <span class="d-none d-md-inline">Reset</span>
                    </a>
                </div>
            </div>
        </form>
        
        <!-- Filter Status Indicator -->
        @if(request()->hasAny(['search', 'kategori_aset', 'kondisi']))
            <div class="mt-3">
                <div class="alert alert-info py-2 mb-0">
                    <i class="fas fa-filter me-2"></i>
                    <strong>Filter aktif:</strong>
                    @if(request('search'))
                        Pencarian "{{ request('search') }}"
                    @endif
                    @if(request('kategori_aset'))
                        | Kategori: {{ ucfirst(request('kategori_aset')) }}
                    @endif
                    @if(request('kondisi'))
                        | Kondisi: {{ ucfirst(str_replace('_', ' ', request('kondisi'))) }}
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Tabel Aset Desa -->
<div class="card shadow-sm">
    <div class="card-body p-3 p-md-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show p-3 p-md-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show p-3 p-md-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" width="5%">No</th>
                        <th scope="col" width="20%">Nama Aset</th>
                        <th scope="col" width="15%" class="d-none d-md-table-cell">Kategori</th>
                        <th scope="col" width="15%" class="d-none d-md-table-cell">Nilai Sekarang</th>
                        <th scope="col" width="15%" class="d-none d-md-table-cell">Tanggal Perolehan</th>
                        <th scope="col" width="15%">Kondisi</th>
                        <th scope="col" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asetDesas as $index => $aset)
                        <tr>
                            <td>{{ $asetDesas->firstItem() + $index }}</td>
                            <td>{{ $aset->nama_aset }}
                                <div class="d-block d-md-none mt-1">
                                    @if($aset->kategori_aset == 'tanah')
                                        <span class="badge bg-success">Tanah</span>
                                    @elseif($aset->kategori_aset == 'bangunan')
                                        <span class="badge bg-primary">Bangunan</span>
                                    @else
                                        <span class="badge bg-info">Inventaris</span>
                                    @endif
                                    <small class="d-block mt-1">Rp {{ number_format($aset->nilai_sekarang ?? $aset->nilai_perolehan ?? 0, 0, ',', '.') }}</small>
                                    <small class="d-block mt-1">{{ $aset->tanggal_perolehan->translatedFormat('d M Y') }}</small>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($aset->kategori_aset == 'tanah')
                                    <span class="badge bg-success">Tanah</span>
                                @elseif($aset->kategori_aset == 'bangunan')
                                    <span class="badge bg-primary">Bangunan</span>
                                @else
                                    <span class="badge bg-info">Inventaris</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">Rp {{ number_format($aset->nilai_sekarang ?? $aset->nilai_perolehan ?? 0, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ $aset->tanggal_perolehan->translatedFormat('d M Y') }}</td>
                            <td>
                                @if($aset->kondisi == 'baik')
                                    <span class="badge bg-success">Baik</span>
                                @elseif($aset->kondisi == 'rusak_ringan')
                                    <span class="badge bg-warning text-dark">Rusak Ringan</span>
                                @else
                                    <span class="badge bg-danger">Rusak Berat</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin-desa.aset-desa.show', $aset) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin-desa.aset-desa.edit', $aset) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $aset->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Modal Hapus -->
                                <div class="modal fade" id="deleteModal{{ $aset->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $aset->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $aset->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3 p-md-4">
                                                <p>Apakah Anda yakin ingin menghapus aset <strong>{{ $aset->nama_aset }}</strong>?</p>
                                                <p class="text-danger"><small>Tindakan ini akan menandai aset sebagai tidak aktif dan tidak akan ditampilkan dalam daftar.</small></p>
                                            </div>
                                            <div class="modal-footer p-2 p-md-3">
                                                <button type="button" class="btn btn-secondary py-2 px-3" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin-desa.aset-desa.destroy', $aset) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger py-2 px-3">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-building fa-3x text-secondary mb-3"></i>
                                    <h5>Belum ada data aset desa</h5>
                                    <p class="text-muted">Silakan tambahkan data aset desa baru</p>
                                    <a href="{{ route('admin-desa.aset-desa.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus-circle me-1"></i> Tambah Aset
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
            {{ $asetDesas->links() }}
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Aset Desa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin-desa.aset-desa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls" required>
                        <div class="form-text">
                            <strong>Format file:</strong> Excel (.xlsx, .xls) maksimal 10MB
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Panduan Import:</h6>
                        <ul class="mb-0">
                            <li>Download template terlebih dahulu untuk format yang benar</li>
                            <li>Data akan otomatis terikat ke desa Anda</li>
                            <li>Format tanggal: dd/mm/yyyy atau yyyy-mm-dd</li>
                            <li>Kategori aset: tanah, bangunan, atau inventaris</li>
                            <li>Kondisi: baik, rusak ringan, atau rusak berat</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection