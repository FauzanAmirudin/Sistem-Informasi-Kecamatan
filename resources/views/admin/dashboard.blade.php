@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data dan statistik kecamatan')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin.monitoring') }}" class="btn btn-outline-primary">
        <i class="fas fa-map-marked-alt me-1"></i>
        Monitoring Desa
    </a>
    <a href="{{ route('admin.statistik') }}" class="btn btn-outline-success">
        <i class="fas fa-chart-bar me-1"></i>
        Statistik Detail
    </a>
</div>
@endsection

@section('admin-content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="card bg-primary text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ $totalDesa }}</div>
                        <div class="small">Total Desa</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="card bg-success text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ number_format($totalPenduduk) }}</div>
                        <div class="small">Total Penduduk</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="card bg-info text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ $totalPerangkat }}</div>
                        <div class="small">Perangkat Desa</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="card bg-warning text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ $totalAsetDesa }}</div>
                        <div class="small">Aset Desa</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="card bg-secondary text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ $totalAsetWarga }}</div>
                        <div class="small">Aset Warga</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-map fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-2 mb-3">
        <div class="card bg-dark text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex flex-column h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="small">Total Nilai Aset</div>
                        <div class="opacity-75">
                            <i class="fas fa-coins fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex align-items-center">
                        <div class="h5 mb-0 fw-bold" style="font-size: 0.9rem; line-height: 1.2;">Rp {{ number_format($totalNilaiAsetDesa + $totalNilaiAsetWarga, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Desa & Statistik Penduduk -->
<div class="row mb-4 g-3">
    <!-- Status Update Desa -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-signal me-2 text-primary"></i>
                    Status Update Desa
                </h5>
                <a href="{{ route('admin.desa.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:180px;">
                    <canvas id="statusUpdateChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small"><i class="fas fa-circle text-success me-1"></i>Update Terbaru (≤7 hari)</span>
                        <span class="badge bg-success">{{ $statusUpdate['hijau'] }} desa</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small"><i class="fas fa-circle text-warning me-1"></i>Perlu Update (≤30 hari)</span>
                        <span class="badge bg-warning">{{ $statusUpdate['kuning'] }} desa</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small"><i class="fas fa-circle text-danger me-1"></i>Butuh Perhatian (>30 hari)</span>
                        <span class="badge bg-danger">{{ $statusUpdate['merah'] }} desa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Gender -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-venus-mars me-2 text-info"></i>
                    Statistik Gender
                </h5>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:180px;">
                    <canvas id="genderChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small"><i class="fas fa-male text-primary me-1"></i>Laki-laki</span>
                        <span class="badge bg-primary">{{ number_format($pendudukPria) }} orang</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small"><i class="fas fa-female text-danger me-1"></i>Perempuan</span>
                        <span class="badge bg-danger">{{ number_format($pendudukWanita) }} orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status KTP -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-id-card me-2 text-success"></i>
                    Status KTP
                </h5>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:200px;">
                    <canvas id="ktpChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-check-circle text-success me-2"></i>Sudah Memiliki KTP</span>
                        <span class="badge bg-success">{{ number_format($pendudukBerKTP) }} orang</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-times-circle text-danger me-2"></i>Belum Memiliki KTP</span>
                        <span class="badge bg-danger">{{ number_format($pendudukBelumKTP) }} orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Nilai Aset -->
<div class="row mb-4 g-3">
    <!-- Statistik Nilai Aset Desa -->
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-building me-2 text-warning"></i>
                    Statistik Nilai Aset Desa
                </h5>
                <a href="{{ route('admin.aset-desa.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="asetDesaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Nilai Aset Warga -->
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-home me-2 text-danger"></i>
                    Statistik Nilai Aset Warga
                </h5>
                <a href="{{ route('admin.aset-tanah-warga.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="asetWargaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Klasifikasi Usia & Top Pekerjaan -->
<div class="row mb-4 g-3">
    <!-- Klasifikasi Usia -->
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-users me-2 text-warning"></i>
                    Klasifikasi Usia Penduduk
                </h5>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="usiaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Pekerjaan -->
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-briefcase me-2 text-secondary"></i>
                    Top 10 Pekerjaan Penduduk
                </h5>
                <a href="{{ route('admin.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i><span class="d-none d-md-inline">Lihat Detail</span>
                </a>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Pekerjaan</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPekerjaan as $index => $pekerjaan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pekerjaan->pekerjaan }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ number_format($pekerjaan->jumlah) }} orang</span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" 
                                             style="width: {{ ($pekerjaan->jumlah / $totalPenduduk) * 100 }}%">
                                            {{ number_format(($pekerjaan->jumlah / $totalPenduduk) * 100, 1) }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Perkembangan Bulanan -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center p-3">
                <h5 class="card-title mb-2 mb-sm-0 fs-6">
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Grafik Perkembangan Bulanan
                </h5>
                <div>
                    <select class="form-select form-select-sm" id="tahunSelect">
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="perkembanganChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4 g-3">
    <div class="col-12 mb-2">
        <h5 class="text-muted fs-6 fw-bold"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
    </div>
    
    <!-- Tambah Desa -->
    <div class="col-6 col-md-4 col-lg-2 mb-2">
        <a href="{{ route('admin.desa.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-2 px-1 h-100">
                <div class="card-body p-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-plus-circle text-primary fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0 small">Tambah Desa</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah Penduduk -->
    <div class="col-6 col-md-4 col-lg-2 mb-2">
        <a href="{{ route('admin.penduduk.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-2 px-1 h-100">
                <div class="card-body p-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-plus text-success fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0 small">Tambah Penduduk</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah Perangkat -->
    <div class="col-6 col-md-4 col-lg-2 mb-2">
        <a href="{{ route('admin.perangkat-desa.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-2 px-1 h-100">
                <div class="card-body p-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-tie text-info fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0 small">Tambah Perangkat</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah Aset Desa -->
    <div class="col-6 col-md-4 col-lg-2 mb-2">
        <a href="{{ route('admin.aset-desa.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-2 px-1 h-100">
                <div class="card-body p-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-building text-warning fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0 small">Tambah Aset Desa</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah User -->
    <div class="col-6 col-md-4 col-lg-2 mb-2">
        <a href="{{ route('admin.users.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-2 px-1 h-100">
                <div class="card-body p-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-cog text-secondary fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0 small">Tambah User</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Upload Dokumen -->
    <div class="col-6 col-md-4 col-lg-2 mb-2">
        <a href="{{ route('admin.dokumen.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-2 px-1 h-100">
                <div class="card-body p-2">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-file-upload text-dark fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0 small">Upload Dokumen</h6>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Update Chart
    const statusUpdateChartElement = document.getElementById('statusUpdateChart');
    if (statusUpdateChartElement) {
        const statusCtx = statusUpdateChartElement.getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Update Terbaru', 'Perlu Update', 'Butuh Perhatian'],
                datasets: [{
                    data: [{{ $statusUpdate['hijau'] }}, {{ $statusUpdate['kuning'] }}, {{ $statusUpdate['merah'] }}],
                    backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Gender Chart
    const genderChartElement = document.getElementById('genderChart');
    if (genderChartElement) {
        const genderCtx = genderChartElement.getContext('2d');
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $pendudukPria }}, {{ $pendudukWanita }}],
                    backgroundColor: ['#0d6efd', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Aset Desa Chart
    const asetDesaChartElement = document.getElementById('asetDesaChart');
    if (asetDesaChartElement) {
        const asetDesaCtx = asetDesaChartElement.getContext('2d');
        new Chart(asetDesaCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($desaList ?? []) !!},
                datasets: [{
                    label: 'Nilai Aset Desa (Rp)',
                    data: {!! json_encode($nilaiAsetDesa ?? []) !!},
                    backgroundColor: '#ffc107',
                    borderColor: '#e0a800',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: false,
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: false,
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Nilai Aset: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }
    
    // Aset Warga Chart
    const asetWargaChartElement = document.getElementById('asetWargaChart');
    if (asetWargaChartElement) {
        const asetWargaCtx = asetWargaChartElement.getContext('2d');
        new Chart(asetWargaCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($desaList ?? []) !!},
                datasets: [{
                    label: 'Nilai Aset Warga (Rp)',
                    data: {!! json_encode($nilaiAsetWarga ?? []) !!},
                    backgroundColor: '#dc3545',
                    borderColor: '#c82333',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Nilai Aset: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }

    // KTP Chart
    const ktpChartElement = document.getElementById('ktpChart');
    if (ktpChartElement) {
        const ktpCtx = ktpChartElement.getContext('2d');
        new Chart(ktpCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sudah KTP', 'Belum KTP'],
                datasets: [{
                    data: [{{ $pendudukBerKTP }}, {{ $pendudukBelumKTP }}],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Usia Chart
    const usiaChartElement = document.getElementById('usiaChart');
    if (usiaChartElement) {
        const usiaCtx = usiaChartElement.getContext('2d');
        new Chart(usiaCtx, {
            type: 'bar',
            data: {
                labels: {!! $klasifikasiUsia->pluck('klasifikasi_usia')->toJson() !!},
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: {!! $klasifikasiUsia->pluck('jumlah')->toJson() !!},
                    backgroundColor: [
                        '#ff6384',
                        '#36a2eb', 
                        '#cc65fe',
                        '#ffce56',
                        '#4bc0c0'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Perkembangan Chart
    const perkembanganChartElement = document.getElementById('perkembanganChart');
    let perkembanganChart;
    
    function initPerkembanganChart() {
        if (perkembanganChartElement) {
            const perkembanganCtx = perkembanganChartElement.getContext('2d');
            
            // Destroy existing chart if it exists
            if (perkembanganChart) {
                perkembanganChart.destroy();
            }
            
            perkembanganChart = new Chart(perkembanganCtx, {
                type: 'line',
                data: {
                    labels: {!! collect($grafikBulanan)->pluck('bulan')->toJson() !!},
                    datasets: [{
                        label: 'Penduduk',
                        data: {!! collect($grafikBulanan)->pluck('penduduk')->toJson() !!},
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.1
                    }, {
                        label: 'Perangkat Desa',
                        data: {!! collect($grafikBulanan)->pluck('perangkat')->toJson() !!},
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
    }
    
    // Initialize chart
    initPerkembanganChart();
    
    // Handle year filter change
    const tahunSelect = document.getElementById('tahunSelect');
    if (tahunSelect) {
        tahunSelect.addEventListener('change', function() {
            const selectedYear = this.value;
            
            // You would typically make an AJAX request here to get new data
            // For now, we'll just reload the page with the selected year
            window.location.href = `{{ route('admin.dashboard') }}?tahun=${selectedYear}`;
        });
    }
});
</script>
@endpush