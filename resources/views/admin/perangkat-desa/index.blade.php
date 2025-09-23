@extends('layouts.admin')

@section('page-title', 'Perangkat Desa')
@section('page-subtitle', 'Kelola data perangkat desa seluruh kecamatan')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.perangkat-desa.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Tambah Perangkat
    </a>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i>
            Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.perangkat-desa.export.excel', request()->query()) }}">
                <i class="fas fa-file-excel me-2"></i>Excel
            </a></li>
        </ul>
    </div>
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
                        <h4 class="mb-0">{{ number_format($totalPerangkat) }}</h4>
                        <small>Total Perangkat</small>
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
                        <h4 class="mb-0">{{ number_format($perangkatAktif) }}</h4>
                        <small>Perangkat Aktif</small>
                    </div>
                    <i class="fas fa-user-check fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($perangkatTidakAktif) }}</h4>
                        <small>Tidak Aktif</small>
                    </div>
                    <i class="fas fa-user-times fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.perangkat-desa.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nama/NIK/Jabatan...">
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" 
                           value="{{ request('jabatan') }}" 
                           placeholder="Nama jabatan...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.perangkat-desa.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data -->
<div class="card shadow-sm mb-2">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Daftar Perangkat Desa ({{ $perangkatDesas->total() }} orang)
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama & NIK</th>
                        <th>Jabatan</th>
                        <th>Desa</th>
                        <th>Masa Tugas</th>
                        <th>Status</th>
                        <th>SK</th>
                        <th>Riwayat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perangkatDesas as $index => $perangkat)
                    <tr>
                        <td>{{ $perangkatDesas->firstItem() + $index }}</td>
                        <td>
                            <div>
                                <strong>{{ $perangkat->nama_lengkap }}</strong>
                                <br><small class="text-muted">
                                    <i class="fas fa-id-card me-1"></i>
                                    {{ $perangkat->nik }}
                                </small>
                                <br><small class="text-muted">
                                    <i class="fas fa-{{ $perangkat->jenis_kelamin == 'L' ? 'male' : 'female' }} me-1"></i>
                                    {{ $perangkat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                                    {{ \Carbon\Carbon::parse($perangkat->tanggal_lahir)->age }} tahun
                                </small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $perangkat->jabatan }}</span>
                            @if($perangkat->jobdesk)
                                <br><small class="text-muted">{{ Str::limit($perangkat->jobdesk, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <small>{{ $perangkat->desa->nama_desa }}</small>
                        </td>
                        <td>
                            <small>
                                <strong>Mulai:</strong> {{ $perangkat->tanggal_mulai_tugas->format('d/m/Y') }}
                                @if($perangkat->tanggal_akhir_tugas)
                                    <br><strong>Berakhir:</strong> {{ $perangkat->tanggal_akhir_tugas->format('d/m/Y') }}
                                @else
                                    <br><span class="text-success">Aktif</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $perangkat->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($perangkat->status) }}
                            </span>
                        </td>
                        <td>
                            @if($perangkat->sk_pengangkatan)
                                <a href="{{ Storage::url($perangkat->sk_pengangkatan) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.perangkat-desa.riwayat', $perangkat) }}" 
                               class="btn btn-sm btn-outline-secondary"
                               data-bs-toggle="tooltip" title="Lihat Riwayat">
                                <i class="fas fa-history"></i>
                                <span class="badge bg-secondary">{{ $perangkat->riwayat->count() }}</span>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.perangkat-desa.show', $perangkat) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   data-bs-toggle="tooltip" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.perangkat-desa.edit', $perangkat) }}" 
                                   class="btn btn-sm btn-outline-warning"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Hapus"
                                        onclick="confirmDelete('{{ route('admin.perangkat-desa.destroy', $perangkat) }}', 'Hapus perangkat {{ $perangkat->nama_lengkap }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>Belum ada data perangkat desa.</p>
                                <a href="{{ route('admin.perangkat-desa.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Perangkat Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($perangkatDesas->hasPages())
    <div class="d-flex justify-content-center py-3 border-top bg-white">
        <nav aria-label="Navigasi halaman">
            {{ $perangkatDesas->links('vendor.pagination.custom-bootstrap-5') }}
        </nav>
    </div>
    @endif
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
@endpush