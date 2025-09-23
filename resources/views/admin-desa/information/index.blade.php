@extends('layouts.admin-desa')

@section('page-title', 'Informasi & Statistik')
@section('page-subtitle', 'Data statistik dan informasi desa')

@section('admin-content')
<!-- Informasi dari Kecamatan -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-newspaper me-2 text-primary"></i>
                    Informasi & Pengumuman dari Kecamatan
                </h5>
            </div>
            <div class="card-body">
                @if($informations->count() > 0)
                    <div class="row">
                        @foreach($informations as $information)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-info">{{ ucfirst($information->kategori) }}</span>
                                        <small class="text-muted">{{ $information->published_at->format('d/m/Y') }}</small>
                                    </div>
                                    <h6 class="card-title text-dark fw-bold mb-2">
                                        {{ Str::limit($information->judul, 50) }}
                                    </h6>
                                    <p class="card-text text-muted small mb-3">
                                        {{ Str::limit(strip_tags($information->konten), 100) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $information->creator->name }}
                                        </small>
                                        <a href="{{ route('admin-desa.information.show', $information) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            Baca
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $informations->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-newspaper fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada informasi</h6>
                        <p class="text-muted small">Belum ada informasi atau pengumuman dari kecamatan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistik Desa -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2 text-success"></i>
                    Statistik Desa {{ Auth::user()->desa->nama_desa ?? '' }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h4 class="mb-1">{{ number_format($totalPenduduk) }}</h4>
                                <small>Total Penduduk</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-user-tie fa-2x mb-2"></i>
                                <h4 class="mb-1">{{ $totalPerangkat }}</h4>
                                <small>Perangkat Desa</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-building fa-2x mb-2"></i>
                                <h4 class="mb-1">{{ $totalAsetDesa }}</h4>
                                <small>Aset Desa</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-home fa-2x mb-2"></i>
                                <h4 class="mb-1">{{ $totalAsetWarga }}</h4>
                                <small>Aset Warga</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <!-- Gender Chart -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-venus-mars me-2 text-info"></i>
                                    Statistik Gender
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($totalPenduduk > 0)
                                    <div class="chart-container" style="position: relative; height: 200px;">
                                        <canvas id="genderChart"></canvas>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small"><i class="fas fa-male text-primary me-1"></i>Laki-laki</span>
                                            <span class="badge bg-primary">{{ number_format($pendudukPria) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small"><i class="fas fa-female text-danger me-1"></i>Perempuan</span>
                                            <span class="badge bg-danger">{{ number_format($pendudukWanita) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data penduduk</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- KTP Chart -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-id-card me-2 text-success"></i>
                                    Status KTP
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($totalPenduduk > 0)
                                    <div class="chart-container" style="position: relative; height: 200px;">
                                        <canvas id="ktpChart"></canvas>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small"><i class="fas fa-check-circle text-success me-1"></i>Sudah KTP</span>
                                            <span class="badge bg-success">{{ number_format($pendudukBerKTP) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small"><i class="fas fa-times-circle text-danger me-1"></i>Belum KTP</span>
                                            <span class="badge bg-danger">{{ number_format($pendudukBelumKTP) }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-id-card fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data penduduk</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Klasifikasi Usia Chart -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-users me-2 text-warning"></i>
                                    Klasifikasi Usia
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($totalPenduduk > 0)
                                    <div class="chart-container" style="position: relative; height: 250px;">
                                        <canvas id="usiaChart"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data penduduk</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Top Pekerjaan -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-briefcase me-2 text-secondary"></i>
                                    Top 5 Pekerjaan
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($topPekerjaan->count() > 0)
                                    <div class="chart-container" style="position: relative; height: 250px;">
                                        <canvas id="pekerjaanChart"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-briefcase fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data pekerjaan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perangkat Desa per Jabatan & Perbandingan Aset -->
                <div class="row">
                    <!-- Perangkat Desa per Jabatan -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user-tie me-2 text-success"></i>
                                    Perangkat Desa per Jabatan
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($perangkatPerJabatan->count() > 0)
                                    <div class="chart-container" style="position: relative; height: 250px;">
                                        <canvas id="perangkatChart"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-tie fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data perangkat desa</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Perbandingan Aset -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2 text-info"></i>
                                    Perbandingan Nilai Aset
                                </h6>
                            </div>
                            <div class="card-body">
                                @if($totalNilaiAsetDesa > 0 || $totalNilaiAsetWarga > 0)
                                    <div class="chart-container" style="position: relative; height: 250px;">
                                        <canvas id="asetComparisonChart"></canvas>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span><i class="fas fa-building text-primary me-2"></i>Nilai Aset Desa</span>
                                            <span class="badge bg-primary">Rp {{ number_format($totalNilaiAsetDesa, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-home text-success me-2"></i>Nilai Aset Warga</span>
                                            <span class="badge bg-success">Rp {{ number_format($totalNilaiAsetWarga, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Belum ada data aset</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nilai Aset Detail -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-coins me-2 text-warning"></i>
                                    Detail Nilai Aset Desa
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <h3 class="text-warning fw-bold">
                                    Rp {{ number_format($totalNilaiAsetDesa, 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">{{ $totalAsetDesa }} aset</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-white">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-home me-2 text-success"></i>
                                    Detail Nilai Aset Warga
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <h3 class="text-success fw-bold">
                                    Rp {{ number_format($totalNilaiAsetWarga, 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">{{ $totalAsetWarga }} aset</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gender Chart
    const genderChartElement = document.getElementById('genderChart');
    if (genderChartElement && {{ $totalPenduduk }} > 0) {
        const genderCtx = genderChartElement.getContext('2d');
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $pendudukPria }}, {{ $pendudukWanita }}],
                    backgroundColor: ['#0d6efd', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // KTP Chart
    const ktpChartElement = document.getElementById('ktpChart');
    if (ktpChartElement && {{ $totalPenduduk }} > 0) {
        const ktpCtx = ktpChartElement.getContext('2d');
        new Chart(ktpCtx, {
            type: 'doughnut',
            data: {
                labels: ['Memiliki KTP', 'Belum KTP'],
                datasets: [{
                    data: [{{ $pendudukBerKTP }}, {{ $pendudukBelumKTP }}],
                    backgroundColor: ['#198754', '#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Usia Chart
    const usiaChartElement = document.getElementById('usiaChart');
    if (usiaChartElement && {{ $totalPenduduk }} > 0) {
        const usiaCtx = usiaChartElement.getContext('2d');
        new Chart(usiaCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($klasifikasiUsia->pluck('klasifikasi_usia')->toArray()) !!},
                datasets: [{
                    label: 'Jumlah Penduduk',
                    data: {!! json_encode($klasifikasiUsia->pluck('jumlah')->toArray()) !!},
                    backgroundColor: '#ffc107',
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
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Perangkat Chart
    const perangkatChartElement = document.getElementById('perangkatChart');
    if (perangkatChartElement && {{ $perangkatPerJabatan->count() }} > 0) {
        const perangkatCtx = perangkatChartElement.getContext('2d');
        new Chart(perangkatCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($perangkatPerJabatan->pluck('jabatan')->toArray()) !!},
                datasets: [{
                    label: 'Jumlah Perangkat',
                    data: {!! json_encode($perangkatPerJabatan->pluck('jumlah')->toArray()) !!},
                    backgroundColor: '#198754',
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
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // Pekerjaan Chart
    const pekerjaanChartElement = document.getElementById('pekerjaanChart');
    if (pekerjaanChartElement && {{ $topPekerjaan->count() }} > 0) {
        const pekerjaanCtx = pekerjaanChartElement.getContext('2d');
        new Chart(pekerjaanCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topPekerjaan->take(5)->pluck('pekerjaan')->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($topPekerjaan->take(5)->pluck('jumlah')->toArray()) !!},
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Aset Comparison Chart
    const asetComparisonChartElement = document.getElementById('asetComparisonChart');
    if (asetComparisonChartElement && ({{ $totalNilaiAsetDesa }} > 0 || {{ $totalNilaiAsetWarga }} > 0)) {
        const asetCtx = asetComparisonChartElement.getContext('2d');
        new Chart(asetCtx, {
            type: 'pie',
            data: {
                labels: ['Aset Desa', 'Aset Tanah Warga'],
                datasets: [{
                    data: [{{ $totalNilaiAsetDesa }}, {{ $totalNilaiAsetWarga }}],
                    backgroundColor: ['#0d6efd', '#198754'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
