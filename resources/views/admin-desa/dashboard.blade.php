@extends('layouts.admin-desa')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan data dan statistik desa')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.penduduk.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-users me-1"></i>
        Data Penduduk
    </a>
    <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-outline-success">
        <i class="fas fa-building me-1"></i>
        Aset Desa
    </a>
</div>
@endsection

@section('admin-content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-4 col-lg-3 mb-3">
        <div class="card bg-primary text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ $totalPenduduk }}</div>
                        <div class="small">Total Penduduk</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-3 mb-3">
        <div class="card bg-success text-white card-hover shadow-sm h-100">
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

    <div class="col-md-4 col-lg-3 mb-3">
        <div class="card bg-info text-white card-hover shadow-sm h-100">
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

    <div class="col-md-4 col-lg-3 mb-3">
        <div class="card bg-warning text-white card-hover shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="h4 mb-0 fw-bold">{{ $totalAsetWarga }}</div>
                        <div class="small">Aset Tanah Warga</div>
                    </div>
                    <div class="opacity-75">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Penduduk & Perangkat Desa -->
<div class="row mb-4">
    <!-- Statistik Penduduk -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2 text-primary"></i>
                    Statistik Penduduk
                </h5>
                <a href="{{ route('admin-desa.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container" style="position: relative; height:200px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container" style="position: relative; height:200px;">
                            <canvas id="ktpChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-male text-primary me-2"></i>Laki-laki</span>
                        <span class="badge bg-primary">{{ $pendudukPria }} orang</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-female text-danger me-2"></i>Perempuan</span>
                        <span class="badge bg-danger">{{ $pendudukWanita }} orang</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-id-card text-success me-2"></i>Memiliki KTP</span>
                        <span class="badge bg-success">{{ $pendudukBerKTP }} orang</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-id-card text-secondary me-2"></i>Belum Memiliki KTP</span>
                        <span class="badge bg-secondary">{{ $pendudukBelumKTP }} orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Perangkat Desa per Jabatan -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tie me-2 text-success"></i>
                    Perangkat Desa per Jabatan
                </h5>
                <a href="{{ route('admin-desa.perangkat-desa.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="perangkatChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Perbandingan Aset & Klasifikasi Usia -->
<div class="row mb-4">
    <!-- Perbandingan Aset -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2 text-info"></i>
                    Perbandingan Aset Desa vs Warga
                </h5>
                <div>
                    <a href="{{ route('admin-desa.aset-desa.index') }}" class="btn btn-sm btn-outline-primary me-1">
                        <i class="fas fa-building me-1"></i>Aset Desa
                    </a>
                    <a href="{{ route('admin-desa.aset-tanah-warga.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-home me-1"></i>Aset Warga
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="asetComparisonChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-building text-primary me-2"></i>Total Nilai Aset Desa</span>
                        <span class="badge bg-primary">Rp {{ number_format($totalNilaiAsetDesa, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-home text-success me-2"></i>Total Nilai Aset Warga</span>
                        <span class="badge bg-success">Rp {{ number_format($totalNilaiAsetWarga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Klasifikasi Usia -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2 text-warning"></i>
                    Klasifikasi Usia Penduduk
                </h5>
                <a href="{{ route('admin-desa.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="usiaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Perkembangan Bulanan & Top Pekerjaan -->
<div class="row mb-4">
    <!-- Perkembangan Bulanan -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2 text-primary"></i>
                    Perkembangan Bulanan
                </h5>
                <div>
                    <select id="tahunSelect" class="form-select form-select-sm" style="width: auto;">
                        @foreach($tahunList as $tahunOption)
                        <option value="{{ $tahunOption }}" {{ request('tahun', date('Y')) == $tahunOption ? 'selected' : '' }}>
                            {{ $tahunOption }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="perkembanganChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Pekerjaan -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-briefcase me-2 text-secondary"></i>
                    Top Pekerjaan
                </h5>
                <a href="{{ route('admin-desa.penduduk.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Detail
                </a>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                    <canvas id="pekerjaanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3">Quick Actions</h5>
    </div>
    
    <!-- Tambah Penduduk -->
    <div class="col-md-4 col-lg-2 col-sm-6 mb-3">
        <a href="{{ route('admin-desa.penduduk.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-3 px-2 h-100">
                <div class="card-body">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-plus text-primary fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0">Tambah Penduduk</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah Perangkat -->
    <div class="col-md-4 col-lg-2 col-sm-6 mb-3">
        <a href="{{ route('admin-desa.perangkat-desa.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-3 px-2 h-100">
                <div class="card-body">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-tie text-info fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0">Tambah Perangkat</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah Aset Desa -->
    <div class="col-md-4 col-lg-2 col-sm-6 mb-3">
        <a href="{{ route('admin-desa.aset-desa.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-3 px-2 h-100">
                <div class="card-body">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-building text-warning fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0">Tambah Aset Desa</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Tambah Aset Tanah Warga -->
    <div class="col-md-4 col-lg-2 col-sm-6 mb-3">
        <a href="{{ route('admin-desa.aset-tanah-warga.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-3 px-2 h-100">
                <div class="card-body">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-home text-success fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0">Tambah Aset Warga</h6>
                </div>
            </div>
        </a>
    </div>
    
    <!-- Upload Dokumen -->
    <div class="col-md-4 col-lg-2 col-sm-6 mb-3">
        <a href="{{ route('admin-desa.dokumen.create') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 text-center py-3 px-2 h-100">
                <div class="card-body">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-file-upload text-dark fa-2x"></i>
                    </div>
                    <h6 class="card-title mb-0">Upload Dokumen</h6>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    if (ktpChartElement) {
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
    
    // Perangkat Chart
    const perangkatChartElement = document.getElementById('perangkatChart');
    if (perangkatChartElement) {
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
    
    // Aset Comparison Chart
    const asetComparisonChartElement = document.getElementById('asetComparisonChart');
    if (asetComparisonChartElement) {
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
    
    // Usia Chart
    const usiaChartElement = document.getElementById('usiaChart');
    if (usiaChartElement) {
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
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Pekerjaan Chart
    const pekerjaanChartElement = document.getElementById('pekerjaanChart');
    if (pekerjaanChartElement) {
        const pekerjaanCtx = pekerjaanChartElement.getContext('2d');
        new Chart(pekerjaanCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topPekerjaan->pluck('pekerjaan')->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($topPekerjaan->pluck('jumlah')->toArray()) !!},
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
    
    // Perkembangan Chart
    function initPerkembanganChart() {
        const perkembanganChartElement = document.getElementById('perkembanganChart');
        if (perkembanganChartElement) {
            const perkembanganCtx = perkembanganChartElement.getContext('2d');
            new Chart(perkembanganCtx, {
                type: 'line',
                data: {
                    labels: {!! collect($grafikBulanan)->pluck('bulan')->toJson() !!},
                    datasets: [{
                        label: 'Penduduk Baru',
                        data: {!! collect($grafikBulanan)->pluck('penduduk')->toJson() !!},
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.1
                    }, {
                        label: 'Perangkat Baru',
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
            window.location.href = `{{ route('admin-desa.dashboard') }}?tahun=${selectedYear}`;
        });
    }
});
</script>
@endpush