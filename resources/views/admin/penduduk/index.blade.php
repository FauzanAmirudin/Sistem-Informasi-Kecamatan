@extends('layouts.admin')

@section('page-title', 'Data Penduduk')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Tambah Penduduk
    </a>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i>
            Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.penduduk.export.excel', request()->query()) }}">
                <i class="fas fa-file-excel me-2"></i>Excel
            </a></li>
            <li><a class="dropdown-item" href="{{ route('admin.penduduk.export.pdf', request()->query()) }}">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a></li>
        </ul>
    </div>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-upload me-1"></i>
            Import
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.penduduk.download-template') }}">
                <i class="fas fa-file-excel me-2"></i>Download Template Excel
            </a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#templatePdfModal">
                <i class="fas fa-file-pdf me-2"></i>Download Template PDF
            </a></li>
            <li><hr class="dropdown-divider"></li>
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
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
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
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
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
        <div class="card bg-danger text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
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
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
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
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.penduduk.index') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="NIK/Nama/Pekerjaan...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desa</label>
                    <select class="form-select" name="desa_id">
                        <option value="">Semua Desa</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                {{ $desa->nama_desa }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-select" name="jenis_kelamin">
                        <option value="">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Klasifikasi Usia</label>
                    <select class="form-select" name="klasifikasi_usia">
                        <option value="">Semua</option>
                        <option value="Balita" {{ request('klasifikasi_usia') == 'Balita' ? 'selected' : '' }}>Balita</option>
                        <option value="Anak-anak" {{ request('klasifikasi_usia') == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                        <option value="Remaja" {{ request('klasifikasi_usia') == 'Remaja' ? 'selected' : '' }}>Remaja</option>
                        <option value="Dewasa" {{ request('klasifikasi_usia') == 'Dewasa' ? 'selected' : '' }}>Dewasa</option>
                        <option value="Lansia" {{ request('klasifikasi_usia') == 'Lansia' ? 'selected' : '' }}>Lansia</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">RT</label>
                    <input type="text" class="form-control" name="rt" value="{{ request('rt') }}" placeholder="RT">
                </div>
                <div class="col-md-1">
                    <label class="form-label">RW</label>
                    <input type="text" class="form-control" name="rw" value="{{ request('rw') }}" placeholder="RW">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status KTP</label>
                    <select class="form-select" name="memiliki_ktp">
                        <option value="">Semua</option>
                        <option value="1" {{ request('memiliki_ktp') == '1' ? 'selected' : '' }}>Sudah KTP</option>
                        <option value="0" {{ request('memiliki_ktp') == '0' ? 'selected' : '' }}>Belum KTP</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.penduduk.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Daftar Penduduk ({{ $penduduks->total() }} orang)
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>JK</th>
                        <th>Usia</th>
                        <th>Pekerjaan</th>
                        <th>RT/RW</th>
                        <th>Desa</th>
                        <th>KTP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penduduks as $index => $penduduk)
                    <tr>
                        <td>{{ $penduduks->firstItem() + $index }}</td>
                        <td>
                            <code>{{ $penduduk->nik }}</code>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $penduduk->nama_lengkap }}</strong>
                                <br><small class="text-muted">{{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir->format('d/m/Y') }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $penduduk->jenis_kelamin == 'L' ? 'primary' : 'danger' }}">
                                {{ $penduduk->jenis_kelamin == 'L' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $penduduk->usia }} th</span>
                            <br><small class="text-muted">{{ $penduduk->klasifikasi_usia }}</small>
                        </td>
                        <td>{{ $penduduk->pekerjaan }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $penduduk->rt }}/{{ $penduduk->rw }}</span>
                        </td>
                        <td>
                            <small>{{ $penduduk->desa->nama_desa }}</small>
                        </td>
                        <td>
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
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.penduduk.show', $penduduk) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   data-bs-toggle="tooltip" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.penduduk.edit', $penduduk) }}" 
                                   class="btn btn-sm btn-outline-warning"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Hapus"
                                        onclick="confirmDelete('{{ route('admin.penduduk.destroy', $penduduk) }}', 'Hapus data {{ $penduduk->nama_lengkap }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>Belum ada data penduduk.</p>
                                <a href="{{ route('admin.penduduk.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Penduduk
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
    <div class="d-flex justify-content-center py-3 border-top bg-white">
        <nav aria-label="Navigasi halaman">
            {{ $penduduks->links('vendor.pagination.custom-bootstrap-5') }}
        </nav>
    </div>
    @endif
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.penduduk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Penduduk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">File Excel/CSV</label>
                        <input type="file" class="form-control" id="file" name="file" 
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format: Excel (.xlsx, .xls) atau CSV. Maksimal 2MB</div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Download template:</strong>
                        <a href="{{ route('admin.penduduk.download-template') }}" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fas fa-download me-1"></i>Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Import
                    </button>
                </div>
            </form>
        </div>
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
</script>

<!-- Modal Template PDF -->
<div class="modal fade" id="templatePdfModal" tabindex="-1" aria-labelledby="templatePdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templatePdfModalLabel">Download Template PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.penduduk.download-template-pdf') }}" method="GET">
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
@endpush