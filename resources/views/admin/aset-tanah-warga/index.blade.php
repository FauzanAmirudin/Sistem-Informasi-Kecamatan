@extends('layouts.admin')

@section('page-title', 'Aset Tanah Warga')
@section('page-subtitle', 'Kelola data aset tanah warga seluruh kecamatan')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.aset-tanah-warga.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Tambah Aset Tanah Warga
    </a>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i>
            Export
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('admin.aset-tanah-warga.export.excel', request()->query()) }}">
                <i class="fas fa-file-excel me-2"></i>Excel
            </a></li>
        </ul>
    </div>
</div>
@endsection

@section('admin-content')
<!-- Filter dan Search -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.aset-tanah-warga.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pencarian</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nama pemilik, NIK, No SPH...">
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
                            <label class="form-label">Status Kepemilikan</label>
                            <select class="form-select" name="status_kepemilikan">
                                <option value="">Semua Status</option>
                                <option value="milik_sendiri" {{ request('status_kepemilikan') == 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                <option value="warisan" {{ request('status_kepemilikan') == 'warisan' ? 'selected' : '' }}>Warisan</option>
                                <option value="hibah" {{ request('status_kepemilikan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                                <option value="jual_beli" {{ request('status_kepemilikan') == 'jual_beli' ? 'selected' : '' }}>Jual Beli</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total Aset Tanah Warga</h6>
                        <h3 class="mb-0">{{ $asetTanahWargas->total() }}</h3>
                    </div>
                    <i class="fas fa-map fa-3x text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Aset Tanah Warga -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fas fa-map me-2"></i>
            Daftar Aset Tanah Warga
        </h5>
    </div>
    <div class="card-body p-0">
        @if($asetTanahWargas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pemilik</th>
                            <th>NIK</th>
                            <th>Desa</th>
                            <th>Nomor SPH</th>
                            <th>Luas Tanah</th>
                            <th>Jenis Tanah</th>
                            <th>Status Kepemilikan</th>
                            <th>Nilai Tanah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asetTanahWargas as $index => $aset)
                        <tr>
                            <td>{{ $asetTanahWargas->firstItem() + $index }}</td>
                            <td>{{ $aset->nama_pemilik }}</td>
                            <td>{{ $aset->nik_pemilik }}</td>
                            <td>{{ $aset->desa->nama_desa }}</td>
                            <td>{{ $aset->nomor_sph }}</td>
                            <td>{{ number_format($aset->luas_tanah, 2) }} m²</td>
                            <td>{{ $aset->jenis_tanah }}</td>
                            <td>
                                @php
                                    $statusClass = match($aset->status_kepemilikan) {
                                        'milik_sendiri' => 'success',
                                        'warisan' => 'primary',
                                        'hibah' => 'info',
                                        'jual_beli' => 'warning',
                                        default => 'secondary'
                                    };
                                    $statusText = match($aset->status_kepemilikan) {
                                        'milik_sendiri' => 'Milik Sendiri',
                                        'warisan' => 'Warisan',
                                        'hibah' => 'Hibah',
                                        'jual_beli' => 'Jual Beli',
                                        default => ucfirst($aset->status_kepemilikan)
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>Rp {{ number_format($aset->nilai_tanah, 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.aset-tanah-warga.show', $aset) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.aset-tanah-warga.edit', $aset) }}" 
                                       class="btn btn-sm btn-outline-warning"
                                       data-bs-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $aset->id }}"
                                            data-bs-toggle="tooltip" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Modal Hapus -->
                                <div class="modal fade" id="deleteModal{{ $aset->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data aset tanah warga:</p>
                                                <ul>
                                                    <li><strong>Nama Pemilik:</strong> {{ $aset->nama_pemilik }}</li>
                                                    <li><strong>NIK:</strong> {{ $aset->nik_pemilik }}</li>
                                                    <li><strong>Nomor SPH:</strong> {{ $aset->nomor_sph }}</li>
                                                </ul>
                                                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.aset-tanah-warga.destroy', $aset) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center py-3 border-top bg-white">
                <nav aria-label="Navigasi halaman">
                    {{ $asetTanahWargas->withQueryString()->links('vendor.pagination.custom-bootstrap-5') }}
                </nav>
            </div>
        @else
            <div class="p-4 text-center">
                <div class="text-muted mb-3">
                    <i class="fas fa-info-circle fa-2x"></i>
                </div>
                <h5>Belum ada data aset tanah warga</h5>
                <p>Silakan tambahkan data aset tanah warga baru.</p>
                <a href="{{ route('admin.aset-tanah-warga.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Aset Tanah Warga
                </a>
            </div>
        @endif
    </div>
</div>
@endsection