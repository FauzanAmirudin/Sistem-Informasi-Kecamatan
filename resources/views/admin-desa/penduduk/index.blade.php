@extends('layouts.admin-desa')

@section('page-title', 'Data Penduduk')
@section('page-subtitle', 'Kelola data penduduk desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.penduduk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Tambah
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
            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#templateExcelModal">
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
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format($totalPenduduk) }}</h4>
                            <small>Total Penduduk</small>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format($pendudukPria) }}</h4>
                            <small>Laki-laki</small>
                        </div>
                        <i class="fas fa-male fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format($pendudukWanita) }}</h4>
                            <small>Perempuan</small>
                        </div>
                        <i class="fas fa-female fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ number_format($pendudukBerKTP) }}</h4>
                            <small>Memiliki KTP</small>
                        </div>
                        <i class="fas fa-id-card fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Filter Data
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin-desa.penduduk.index') }}" method="GET" id="filter-form">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Pencarian</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Nama / NIK" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">Semua</option>
                                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                                <label for="rt" class="form-label">RT</label>
                                <select class="form-select" id="rt" name="rt">
                                    <option value="">Semua</option>
                                    @foreach($rtList as $rt)
                                        <option value="{{ $rt }}" {{ request('rt') == $rt ? 'selected' : '' }}>{{ $rt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="rw" class="form-label">RW</label>
                                <select class="form-select" id="rw" name="rw">
                                    <option value="">Semua</option>
                                    @foreach($rwList as $rw)
                                        <option value="{{ $rw }}" {{ request('rw') == $rw ? 'selected' : '' }}>{{ $rw }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <a href="{{ route('admin-desa.penduduk.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i> Reset Filter
                                </a>
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
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Data Penduduk ({{ $penduduks->total() }} orang)
                    </h5>
                    <div class="d-flex align-items-center">
                        @if($penduduks->count() > 0)
                            <span class="badge bg-primary me-2">{{ $penduduks->count() }} dari {{ $penduduks->total() }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="15%">NIK</th>
                                    <th width="20%">Nama Lengkap</th>
                                    <th width="10%" class="text-center">JK</th>
                                    <th width="12%" class="text-center">Usia</th>
                                    <th width="10%" class="text-center">RT/RW</th>
                                    <th width="10%" class="text-center">KTP</th>
                                    <th width="18%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penduduks as $key => $penduduk)
                                    <tr>
                                        <td class="text-center">{{ $penduduks->firstItem() + $key }}</td>
                                        <td>
                                            <code class="fs-6">{{ $penduduk->nik }}</code>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $penduduk->nama_lengkap }}</strong>
                                                <br><small class="text-muted">{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir->format('d/m/Y') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($penduduk->jenis_kelamin == 'L')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-male me-1"></i> L
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-female me-1"></i> P
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $penduduk->usia }} th</span>
                                            <br><small class="text-muted">{{ $penduduk->klasifikasi_usia }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $penduduk->rt }}/{{ $penduduk->rw }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($penduduk->memiliki_ktp)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Ada
                                                </span>
                                                @if($penduduk->tanggal_rekam_ktp)
                                                    <br><small class="text-muted">{{ $penduduk->tanggal_rekam_ktp->format('d/m/Y') }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times"></i> Belum
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin-desa.penduduk.show', $penduduk) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   data-bs-toggle="tooltip" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin-desa.penduduk.edit', $penduduk) }}" 
                                                   class="btn btn-sm btn-outline-warning"
                                                   data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" title="Hapus"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $penduduk->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Modal Konfirmasi Hapus -->
                                            <div class="modal fade" id="deleteModal{{ $penduduk->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $penduduk->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $penduduk->id }}">
                                                                <i class="fas fa-trash me-2"></i>Konfirmasi Hapus
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-0">Apakah Anda yakin ingin menghapus data penduduk <strong>{{ $penduduk->nama_lengkap }}</strong>?</p>
                                                            <p class="text-danger small mt-2 mb-0">
                                                                <i class="fas fa-exclamation-triangle me-1"></i> Tindakan ini tidak dapat dibatalkan!
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-1"></i> Batal
                                                            </button>
                                                            <form action="{{ route('admin-desa.penduduk.destroy', $penduduk) }}" method="POST" class="d-inline">
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
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data penduduk</h5>
                                                <p class="text-muted mb-3">Mulai dengan menambahkan data penduduk pertama</p>
                                                <a href="{{ route('admin-desa.penduduk.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus-circle me-1"></i> Tambah Penduduk
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($penduduks->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center py-2">
                        <nav aria-label="Navigasi halaman">
                            {{ $penduduks->appends(request()->except('page'))->links('vendor.pagination.custom-bootstrap-5') }}
                        </nav>
                    </div>
                </div>
                @endif
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
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#templateExcelModal">
                                        <i class="fas fa-download me-1"></i> Download Template
                                    </button>
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

    <!-- Modal Template Excel -->
    <div class="modal fade" id="templateExcelModal" tabindex="-1" aria-labelledby="templateExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="templateExcelModalLabel">Download Template Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin-desa.penduduk.template') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="jumlah_baris_excel" class="form-label">Jumlah Baris Kosong</label>
                            <input type="number" class="form-control" id="jumlah_baris_excel" name="jumlah_baris" value="50" min="1" max="1000">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Pilih Kolom</label>
                            <div class="row">
                                @php
                                    $koloms = ['nik','nama_lengkap','jenis_kelamin','tempat_lahir','tanggal_lahir','agama','status_perkawinan','pekerjaan','pendidikan_terakhir','alamat','rt','rw','desa','memiliki_ktp','tanggal_rekam_ktp'];
                                @endphp
                                @foreach($koloms as $k)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kolom[]" value="{{ $k }}" id="col-{{ $k }}" checked>
                                        <label class="form-check-label" for="col-{{ $k }}">{{ str_replace('_',' ', ucfirst($k)) }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-text">Biarkan semua tercentang bila ingin kolom lengkap.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-download me-1"></i>Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection