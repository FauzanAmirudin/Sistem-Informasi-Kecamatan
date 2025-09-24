@extends('layouts.admin-desa')

@section('page-title', 'Perangkat Desa')
@section('page-subtitle', 'Kelola data perangkat desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.perangkat-desa.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i>
        Tambah Perangkat
    </a>
    <a href="{{ route('admin-desa.perangkat-desa.export.excel') }}" class="btn btn-success">
        <i class="fas fa-file-excel me-1"></i>
        Export Excel
    </a>
</div>
@endsection

@section('admin-content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Perangkat</h6>
                        <h2 class="mt-2 mb-0">{{ $totalPerangkat }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Perangkat Aktif</h6>
                        <h2 class="mt-2 mb-0">{{ $perangkatAktif }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-user-check fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-secondary text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Tidak Aktif</h6>
                        <h2 class="mt-2 mb-0">{{ $perangkatTidakAktif }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-user-times fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin-desa.perangkat-desa.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nama / NIK / Jabatan" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" 
                       placeholder="Filter jabatan" value="{{ request('jabatan') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="d-grid gap-2 w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Daftar Perangkat Desa {{ $desa->nama_desa }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama & NIK</th>
                        <th width="15%">Jabatan</th>
                        <th width="20%">Masa Tugas</th>
                        <th width="10%">Status</th>
                        <th width="10%">SK</th>
                        <th width="10%">Riwayat</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perangkatDesas as $index => $perangkat)
                    <tr>
                        <td>{{ $perangkatDesas->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">{{ $perangkat->nama_lengkap }}</span>
                                <small class="text-muted">NIK: {{ $perangkat->nik }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $perangkat->jabatan }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <small>Mulai: {{ $perangkat->tanggal_mulai_tugas->format('d/m/Y') }}</small>
                                @if($perangkat->tanggal_akhir_tugas)
                                    <small>Sampai: {{ $perangkat->tanggal_akhir_tugas->format('d/m/Y') }}</small>
                                @else
                                    <small class="text-success">Masih Aktif</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $perangkat->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $perangkat->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($perangkat->sk_pengangkatan)
                                <a href="{{ $perangkat->file_url }}" 
                                   target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @else
                                <span class="badge bg-warning text-dark">Belum Ada</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin-desa.perangkat-desa.riwayat', $perangkat) }}" 
                               class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-history"></i>
                                <span class="badge bg-secondary">{{ $perangkat->riwayat->count() }}</span>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin-desa.perangkat-desa.show', $perangkat) }}" 
                                   class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin-desa.perangkat-desa.edit', $perangkat) }}" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $perangkat->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="deleteModal{{ $perangkat->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus data perangkat <strong>{{ $perangkat->nama_lengkap }}</strong>?</p>
                                            <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan akan menghapus semua riwayat perubahan data.</small></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin-desa.perangkat-desa.destroy', $perangkat) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus Data</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <h5>Belum ada data perangkat desa</h5>
                                <p class="text-muted">Silakan tambahkan data perangkat desa baru</p>
                                <a href="{{ route('admin-desa.perangkat-desa.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    Tambah Perangkat
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">Menampilkan {{ $perangkatDesas->firstItem() ?? 0 }} - {{ $perangkatDesas->lastItem() ?? 0 }} dari {{ $perangkatDesas->total() }} data</small>
            </div>
            <div>
                {{ $perangkatDesas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection