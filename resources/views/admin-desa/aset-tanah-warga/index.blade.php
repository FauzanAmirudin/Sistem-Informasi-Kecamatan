@extends('layouts.admin-desa')

@section('page-title', 'Aset Tanah Warga')
@section('page-subtitle', 'Kelola data aset tanah warga')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.aset-tanah-warga.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i>
        Tambah SPH
    </a>
    <a href="{{ route('admin-desa.aset-tanah-warga.exportExcel') }}" class="btn btn-success">
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
                        <h6 class="card-title mb-0">Total SPH</h6>
                        <h2 class="mt-2 mb-0">{{ $totalAsetTanah }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-home fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="card-title mb-0">Distribusi Jenis Tanah</h6>
            </div>
            <div class="card-body">
                <canvas id="jenisTanahChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin-desa.aset-tanah-warga.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nama / NIK / Nomor SPH / Lokasi" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="jenis_tanah" class="form-label">Jenis Tanah</label>
                <select class="form-select" id="jenis_tanah" name="jenis_tanah">
                    <option value="">Semua Jenis</option>
                    <option value="tanah_kering" {{ request('jenis_tanah') == 'tanah_kering' ? 'selected' : '' }}>Tanah Kering</option>
                    <option value="tanah_sawah" {{ request('jenis_tanah') == 'tanah_sawah' ? 'selected' : '' }}>Tanah Sawah</option>
                    <option value="tanah_pekarangan" {{ request('jenis_tanah') == 'tanah_pekarangan' ? 'selected' : '' }}>Tanah Pekarangan</option>
                    <option value="tanah_perkebunan" {{ request('jenis_tanah') == 'tanah_perkebunan' ? 'selected' : '' }}>Tanah Perkebunan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status_kepemilikan" class="form-label">Status Kepemilikan</label>
                <select class="form-select" id="status_kepemilikan" name="status_kepemilikan">
                    <option value="">Semua Status</option>
                    <option value="milik_sendiri" {{ request('status_kepemilikan') == 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                    <option value="warisan" {{ request('status_kepemilikan') == 'warisan' ? 'selected' : '' }}>Warisan</option>
                    <option value="hibah" {{ request('status_kepemilikan') == 'hibah' ? 'selected' : '' }}>Hibah</option>
                    <option value="jual_beli" {{ request('status_kepemilikan') == 'jual_beli' ? 'selected' : '' }}>Jual Beli</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="d-grid gap-2 w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin-desa.aset-tanah-warga.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Aset Tanah Warga -->
<div class="card shadow-sm">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" width="5%">No</th>
                        <th scope="col" width="15%">Nama Pemilik</th>
                        <th scope="col" width="10%">NIK</th>
                        <th scope="col" width="10%">Nomor SPH</th>
                        <th scope="col" width="15%">Jenis Tanah</th>
                        <th scope="col" width="10%">Luas (m²)</th>
                        <th scope="col" width="15%">Nilai Total</th>
                        <th scope="col" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($asetTanahWargas as $index => $aset)
                        <tr>
                            <td>{{ $asetTanahWargas->firstItem() + $index }}</td>
                            <td>{{ $aset->nama_pemilik }}</td>
                            <td>{{ $aset->nik_pemilik }}</td>
                            <td>{{ $aset->nomor_sph }}</td>
                            <td>
                                @php
                                    $jenisTanahLabel = '';
                                    $badgeClass = '';
                                    
                                    switch($aset->jenis_tanah) {
                                        case 'tanah_kering':
                                            $jenisTanahLabel = 'Tanah Kering';
                                            $badgeClass = 'bg-warning text-dark';
                                            break;
                                        case 'tanah_sawah':
                                            $jenisTanahLabel = 'Tanah Sawah';
                                            $badgeClass = 'bg-success';
                                            break;
                                        case 'tanah_pekarangan':
                                            $jenisTanahLabel = 'Tanah Pekarangan';
                                            $badgeClass = 'bg-info';
                                            break;
                                        case 'tanah_perkebunan':
                                            $jenisTanahLabel = 'Tanah Perkebunan';
                                            $badgeClass = 'bg-primary';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $jenisTanahLabel }}</span>
                            </td>
                            <td>{{ number_format($aset->luas_tanah, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($aset->getNilaiTanahAttribute(), 0, ',', '.') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin-desa.aset-tanah-warga.show', $aset) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin-desa.aset-tanah-warga.edit', $aset) }}" class="btn btn-sm btn-warning">
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
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data SPH dengan nomor <strong>{{ $aset->nomor_sph }}</strong> milik <strong>{{ $aset->nama_pemilik }}</strong>?</p>
                                                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin-desa.aset-tanah-warga.destroy', $aset) }}" method="POST">
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-3">Tidak ada data aset tanah warga</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $asetTanahWargas->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart
        const ctx = document.getElementById('jenisTanahChart').getContext('2d');
        const jenisTanahChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dataGrafik['labels']) !!},
                datasets: [{
                    label: 'Jumlah SPH',
                    data: {!! json_encode($dataGrafik['data']) !!},
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.7)',  // Tanah Kering
                        'rgba(40, 167, 69, 0.7)',  // Tanah Sawah
                        'rgba(23, 162, 184, 0.7)',  // Tanah Pekarangan
                        'rgba(0, 123, 255, 0.7)'    // Tanah Perkebunan
                    ],
                    borderColor: [
                        'rgba(255, 193, 7, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(0, 123, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush