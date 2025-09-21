@extends('layouts.admin-desa')

@section('page-title', 'Data Penduduk Desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.penduduk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Tambah Penduduk
    </a>
    <div class="dropdown d-inline-block">
        <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-export me-1"></i> Export
        </button>
        <div class="dropdown-menu" aria-labelledby="exportDropdown">
            <a class="dropdown-item" href="{{ route('admin-desa.penduduk.export-excel') }}?{{ http_build_query(request()->all()) }}">
                <i class="fas fa-file-excel me-1"></i> Excel
            </a>
            <a class="dropdown-item" href="{{ route('admin-desa.penduduk.export.pdf') }}?{{ http_build_query(request()->all()) }}">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </a>
        </div>
    </div>
    <div class="dropdown d-inline-block">
        <button class="btn btn-info dropdown-toggle" type="button" id="importDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-import me-1"></i> Import
        </button>
        <div class="dropdown-menu" aria-labelledby="importDropdown">
            <a class="dropdown-item" href="{{ route('admin-desa.penduduk.template') }}">
                <i class="fas fa-file-excel me-1"></i> Download Template Excel
            </a>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#templatePdfModal">
                <i class="fas fa-file-pdf me-1"></i> Download Template PDF
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-upload me-1"></i> Import Data
            </a>
        </div>
    </div>
</div>
@endsection

@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Penduduk</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white mb-3 shadow-sm">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Total Penduduk</h6>
                                            <h2 class="mt-2 mb-0">{{ $totalPenduduk }}</h2>
                                        </div>
                                        <i class="fas fa-users fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white mb-3 shadow-sm">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Laki-laki</h6>
                                            <h2 class="mt-2 mb-0">{{ $pendudukPria }}</h2>
                                        </div>
                                        <i class="fas fa-male fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white mb-3 shadow-sm">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Perempuan</h6>
                                            <h2 class="mt-2 mb-0">{{ $pendudukWanita }}</h2>
                                        </div>
                                        <i class="fas fa-female fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white mb-3 shadow-sm">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Memiliki KTP</h6>
                                            <h2 class="mt-2 mb-0">{{ $pendudukBerKTP }}</h2>
                                        </div>
                                        <i class="fas fa-id-card fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filter Data</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin-desa.penduduk.index') }}" method="GET" id="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="search" class="form-label">Cari</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="search" name="search" placeholder="Nama / NIK" value="{{ request('search') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="">Semua</option>
                                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="klasifikasi_usia" class="form-label">Klasifikasi Usia</label>
                                    <select class="form-select" id="klasifikasi_usia" name="klasifikasi_usia">
                                        <option value="">Semua</option>
                                        <option value="Balita" {{ request('klasifikasi_usia') == 'Balita' ? 'selected' : '' }}>Balita</option>
                                        <option value="Anak" {{ request('klasifikasi_usia') == 'Anak' ? 'selected' : '' }}>Anak</option>
                                        <option value="Remaja" {{ request('klasifikasi_usia') == 'Remaja' ? 'selected' : '' }}>Remaja</option>
                                        <option value="Dewasa" {{ request('klasifikasi_usia') == 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                                        <option value="Lansia" {{ request('klasifikasi_usia') == 'Lansia' ? 'selected' : '' }}>Lansia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="rt" class="form-label">RT</label>
                                    <select class="form-select" id="rt" name="rt">
                                        <option value="">Semua</option>
                                        @foreach($rtList as $rt)
                                            <option value="{{ $rt }}" {{ request('rt') == $rt ? 'selected' : '' }}>{{ $rt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label for="rw" class="form-label">RW</label>
                                    <select class="form-select" id="rw" name="rw">
                                        <option value="">Semua</option>
                                        @foreach($rwList as $rw)
                                            <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>{{ $rw }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Data Penduduk</h5>
                    <span class="badge bg-primary">{{ $penduduks->total() }} Data</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">NIK</th>
                                <th width="20%">Nama Lengkap</th>
                                <th width="15%">Jenis Kelamin</th>
                                <th width="15%">Usia</th>
                                <th width="10%">RT/RW</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penduduks as $key => $penduduk)
                                <tr>
                                    <td>{{ $penduduks->firstItem() + $key }}</td>
                                    <td>{{ $penduduk->nik }}</td>
                                    <td>{{ $penduduk->nama_lengkap }}</td>
                                    <td>
                                        @if($penduduk->jenis_kelamin == 'L')
                                            <span class="badge bg-info text-white"><i class="fas fa-male me-1"></i> Laki-laki</span>
                                        @else
                                            <span class="badge bg-danger text-white"><i class="fas fa-female me-1"></i> Perempuan</span>
                                        @endif
                                    </td>
                                    <td>{{ $penduduk->usia }} tahun <span class="badge bg-secondary">{{ $penduduk->klasifikasi_usia }}</span></td>
                                    <td>{{ $penduduk->rt }}/{{ $penduduk->rw }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin-desa.penduduk.show', $penduduk->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin-desa.penduduk.edit', $penduduk->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $penduduk->id }}" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal Konfirmasi Hapus -->
                                        <div class="modal fade" id="deleteModal{{ $penduduk->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $penduduk->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $penduduk->id }}"><i class="fas fa-trash me-2"></i>Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">Apakah Anda yakin ingin menghapus data penduduk <strong>{{ $penduduk->nama_lengkap }}</strong>?</p>
                                                        <p class="text-danger small mt-2 mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Tindakan ini tidak dapat dibatalkan!</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i> Batal
                                                        </button>
                                                        <form action="{{ route('admin-desa.penduduk.destroy', $penduduk->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash me-1"></i> Hapus
                                                            </button>
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
                                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                            <p class="mb-2">Tidak ada data penduduk</p>
                                            <a href="{{ route('admin-desa.penduduk.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah Penduduk
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">
                    @if($penduduks->hasPages())
                    <div class="d-flex justify-content-center py-2">
                        <nav aria-label="Navigasi halaman">
                            {{ $penduduks->appends(request()->except('page'))->links('vendor.pagination.custom-bootstrap-5') }}
                        </nav>
                    </div>
                    @else
                    <div class="text-center py-2 text-muted small">
                        Menampilkan {{ $penduduks->count() }} dari {{ $penduduks->total() }} data
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="fas fa-file-import me-2"></i>Import Data Penduduk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin-desa.penduduk.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">File Excel</label>
                            <input type="file" class="form-control" id="file" name="file" required accept=".xlsx,.xls,.csv">
                            <div class="form-text"><i class="fas fa-info-circle me-1"></i>Format file: .xlsx, .xls, .csv (max: 10MB)</div>
                        </div>
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-1">Pastikan format data sesuai dengan template.</p>
                                    <a href="{{ route('admin-desa.penduduk.template') }}" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-1"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Template PDF -->
    <div class="modal fade" id="templatePdfModal" tabindex="-1" aria-labelledby="templatePdfModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="templatePdfModalLabel">Download Template PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin-desa.penduduk.download-template-pdf') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="jumlah_baris" class="form-label">Jumlah Baris Data</label>
                            <input type="number" class="form-control" id="jumlah_baris" name="jumlah_baris" 
                                   value="50" min="10" max="200" required>
                            <div class="form-text">
                                Masukkan jumlah baris data yang diinginkan (10-200 baris)
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Informasi Template PDF:</h6>
                            <ul class="mb-0">
                                <li>Template akan berisi form kosong untuk pengisian data penduduk</li>
                                <li>Dapat diisi secara manual dan kemudian di-scan untuk input data</li>
                                <li>Format kolom sudah disesuaikan dengan struktur database</li>
                                <li>Termasuk petunjuk pengisian yang lengkap</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download me-1"></i>Download Template PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection