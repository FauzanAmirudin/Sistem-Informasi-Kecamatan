@extends('layouts.admin')

@section('page-title', 'Statistik Detail')
@section('page-subtitle', 'Analisis data dan laporan statistik')

@push('styles')
<style>
    /* Transisi dan efek hover */
    .transition-all {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.08) !important;
    }
    
    /* Card styling konsisten */
    .card {
        border-radius: 0.5rem !important;
        border: none;
    }
    .card-header {
        padding: 0.75rem 1rem;
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .card-body {
        padding: 1rem;
    }
    
    /* Chart container */
    .chart-container {
        position: relative;
        margin: auto;
        height: 100%;
        width: 100%;
    }
    
    /* Statistik values */
    .stats-value {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .stats-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    /* Card heading konsisten */
    .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0;
    }
    
    /* Spacing konsisten */
    .row {
        margin-bottom: 1.5rem;
    }
    
    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    /* Status badge improvements */
    .badge.bg-success {
        background-color: #198754 !important;
        color: #ffffff !important;
    }
    
    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #000000 !important;
    }
    
    .badge.bg-danger {
        background-color: #dc3545 !important;
        color: #ffffff !important;
    }
    
    /* Status card header improvements */
    .card-header.bg-success {
        background-color: #198754 !important;
        color: #ffffff !important;
    }
    
    .card-header.bg-warning {
        background-color: #ffc107 !important;
        color: #000000 !important;
    }
    
    .card-header.bg-danger {
        background-color: #dc3545 !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        <span class="d-none d-md-inline">Kembali</span>
    </a>
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fas fa-download me-1"></i>
            <span class="d-none d-md-inline">Export Data</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-1">
            <li><h6 class="dropdown-header">Format Excel</h6></li>
            <li><button class="dropdown-item" id="btn-export-excel-penduduk">
                <i class="fas fa-file-excel me-2 text-success"></i>Data Penduduk
            </button></li>
            <li><button class="dropdown-item" id="btn-export-excel-aset">
                <i class="fas fa-file-excel me-2 text-success"></i>Data Aset
            </button></li>
            <li><button class="dropdown-item" id="btn-export-excel-perangkat">
                <i class="fas fa-file-excel me-2 text-success"></i>Data Perangkat
            </button></li>
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header">Format PDF</h6></li>
            <li><button class="dropdown-item" id="btn-export-pdf-penduduk">
                <i class="fas fa-file-pdf me-2 text-danger"></i>Data Penduduk
            </button></li>
            <li><button class="dropdown-item" id="btn-export-pdf-aset">
                <i class="fas fa-file-pdf me-2 text-danger"></i>Data Aset
            </button></li>
            <li><button class="dropdown-item" id="btn-export-pdf-perangkat">
                <i class="fas fa-file-pdf me-2 text-danger"></i>Data Perangkat
            </button></li>
        </ul>
    </div>
</div>
@endsection

@section('admin-content')
<!-- Ringkasan Statistik Kecamatan -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="card shadow-sm overflow-hidden">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-chart-pie me-1 text-primary"></i>
                    Ringkasan Statistik Kecamatan
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="row g-2">
                    @foreach($statistikPerDesa as $desa)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden transition-all hover-shadow">
                            <div class="card-header py-1 @if($desa['status_update'] == 'hijau') bg-success text-white @elseif($desa['status_update'] == 'kuning') bg-warning text-dark @else bg-danger text-white @endif">
                                <h6 class="mb-0 fw-bold small">{{ $desa['nama_desa'] }}</h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="row text-center g-1 mb-2">
                                    <div class="col-4">
                                        <div class="p-1 rounded-3 bg-light">
                                            <h5 class="mb-0 fw-bold">{{ number_format($desa['total_penduduk']) }}</h5>
                                            <small class="text-muted" style="font-size: 0.7rem;">Penduduk</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-1 rounded-3 bg-light">
                                            <h5 class="mb-0 fw-bold">{{ number_format($desa['total_perangkat']) }}</h5>
                                            <small class="text-muted" style="font-size: 0.7rem;">Perangkat</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-1 rounded-3 bg-light">
                                            <h5 class="mb-0 fw-bold">{{ number_format($desa['total_aset']) }}</h5>
                                            <small class="text-muted" style="font-size: 0.7rem;">Aset</small>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between px-1 py-1">
                                    <span class="badge bg-light text-primary border border-primary" style="font-size: 0.65rem;"><i class="fas fa-male"></i> {{ number_format($desa['penduduk_pria']) }}</span>
                                    <span class="badge bg-light text-danger border border-danger" style="font-size: 0.65rem;"><i class="fas fa-female"></i> {{ number_format($desa['penduduk_wanita']) }}</span>
                                    <span class="badge bg-light text-warning border border-warning" style="font-size: 0.65rem;"><i class="fas fa-coins"></i> {{ number_format($desa['nilai_aset']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Perbandingan Antar Desa -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="card shadow-sm overflow-hidden">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-chart-bar me-1 text-success"></i>
                    Perbandingan Antar Desa
                </h5>
                <button class="btn btn-sm btn-outline-primary" id="toggleChartView">
                    <i class="fas fa-exchange-alt me-1"></i>
                    <span class="d-none d-md-inline">Ubah Tampilan</span>
                </button>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:50vh; width:100%">
                    <canvas id="perbandinganDesaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Penduduk & Aset -->
<div class="row g-3">
    <!-- Chart Klasifikasi Usia -->
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-users me-1 text-warning"></i>
                    Klasifikasi Usia Penduduk
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:40vh; width:100%">
                    <canvas id="klasifikasiUsiaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Chart Pekerjaan -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-briefcase me-1 text-info"></i>
                    Top 5 Pekerjaan Penduduk
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="chart-container" style="position: relative; height:40vh; width:100%">
                    <canvas id="pekerjaanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Pendidikan & Statistik Aset -->
<div class="row">
    <!-- Chart Pendidikan -->
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                    Tingkat Pendidikan Penduduk
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:40vh; width:100%">
                    <canvas id="pendidikanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik Aset -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-building me-2 text-danger"></i>
                    Statistik Aset
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-1 mb-1">
                    <div class="col-6">
                        <div class="p-1 rounded-3 bg-light text-center transition-all hover-shadow">
                            <div class="stats-value text-primary">{{ number_format($totalAsetDesa) }}</div>
                            <div class="stats-label">Total Aset Desa</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1 rounded-3 bg-light text-center transition-all hover-shadow">
                            <div class="stats-value text-success">{{ number_format($totalAsetWarga) }}</div>
                            <div class="stats-label">Total Aset Warga</div>
                        </div>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <div class="p-1 rounded-3 bg-light text-center transition-all hover-shadow">
                            <div class="stats-value text-warning">Rp {{ number_format($totalNilaiAsetDesa, 0, ',', '.') }}</div>
                            <div class="stats-label">Nilai Aset Desa</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-1 rounded-3 bg-light text-center transition-all hover-shadow">
                            <div class="stats-value text-danger">Rp {{ number_format($totalNilaiAsetWarga, 0, ',', '.') }}</div>
                            <div class="stats-label">Nilai Aset Warga</div>
                        </div>
                    </div>
                </div>
                <div class="chart-container mt-3" style="position: relative; height:40vh; width:100%; margin: 0 auto;">
                    <canvas id="asetChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Detail Statistik -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center p-3">
                <h5 class="card-title mb-0 fs-6">
                    <i class="fas fa-table me-1 text-secondary"></i>
                    Detail Statistik Per Desa
                </h5>
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" class="form-control" id="searchTable" placeholder="Cari desa...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-2 px-3 border-0">Desa</th>
                                <th class="py-2 px-3 border-0 text-center">Total Penduduk</th>
                                <th class="py-2 px-3 border-0 text-center d-none d-md-table-cell">Laki-laki</th>
                                <th class="py-2 px-3 border-0 text-center d-none d-md-table-cell">Perempuan</th>
                                <th class="py-2 px-3 border-0 text-center d-none d-md-table-cell">Perangkat Desa</th>
                                <th class="py-2 px-3 border-0 text-center d-none d-md-table-cell">Jumlah Aset</th>
                                <th class="py-2 px-3 border-0 text-end d-none d-md-table-cell">Nilai Aset (Rp)</th>
                                <th class="py-2 px-3 border-0 text-center">Status Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statistikPerDesa as $desa)
                            <tr class="transition-all">
                                <td class="py-2 px-3 fw-bold small">{{ $desa['nama_desa'] }}</td>
                                <td class="py-2 px-3 text-center small">{{ number_format($desa['total_penduduk']) }}</td>
                                <td class="py-2 px-3 text-center small d-none d-md-table-cell"><i class="fas fa-male text-primary"></i> {{ number_format($desa['penduduk_pria']) }}</td>
                                <td class="py-2 px-3 text-center small d-none d-md-table-cell"><i class="fas fa-female text-danger"></i> {{ number_format($desa['penduduk_wanita']) }}</td>
                                <td class="py-2 px-3 text-center small d-none d-md-table-cell">{{ number_format($desa['total_perangkat']) }}</td>
                                <td class="py-2 px-3 text-center small d-none d-md-table-cell">{{ number_format($desa['total_aset']) }}</td>
                                <td class="py-2 px-3 text-end fw-bold small d-none d-md-table-cell">{{ number_format($desa['nilai_aset'], 0, ',', '.') }}</td>
                                <td class="py-2 px-3 text-center">
                                    @if($desa['status_update'] == 'hijau')
                                        <span class="badge rounded-pill bg-success text-white small">
                                            <i class="fas fa-check-circle me-1"></i>Terbaru
                                        </span>
                                    @elseif($desa['status_update'] == 'kuning')
                                        <span class="badge rounded-pill bg-warning text-dark small">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Perlu Update
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-danger text-white small">
                                            <i class="fas fa-times-circle me-1"></i>Belum Update
                                        </span>
                                    @endif
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
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<!-- SheetJS (xlsx) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- Loading overlay -->
<style>
#loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255,255,255,0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    flex-direction: column;
    color: var(--bs-dark);
    display: none;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid rgba(0,0,0,0.1);
    border-radius: 50%;
    border-top-color: var(--bs-primary);
    animation: spin 1s ease-in-out infinite;
    margin-bottom: 15px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<div id="loading-overlay">
    <div class="spinner"></div>
    <div id="loading-message">Memproses...</div>
</div>

<script>
function showLoading(message = 'Memproses...') {
    document.getElementById('loading-message').textContent = message;
    document.getElementById('loading-overlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Warna untuk chart
    const colors = {
        primary: '#0d6efd',
        success: '#198754',
        info: '#0dcaf0',
        warning: '#ffc107',
        danger: '#dc3545',
        secondary: '#6c757d',
        light: '#f8f9fa',
        dark: '#212529',
        indigo: '#6610f2',
        purple: '#6f42c1',
        pink: '#d63384',
        orange: '#fd7e14',
        teal: '#20c997',
    };
    
    // Chart Perbandingan Antar Desa
    const perbandinganDesaCtx = document.getElementById('perbandinganDesaChart').getContext('2d');
    const perbandinganDesaChart = new Chart(perbandinganDesaCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($grafikPerbandingan['labels']) !!},
            datasets: [
                {
                    label: 'Penduduk',
                    data: {!! json_encode($grafikPerbandingan['penduduk']) !!},
                    backgroundColor: colors.primary,
                    borderColor: colors.primary,
                    borderWidth: 1
                },
                {
                    label: 'Perangkat Desa',
                    data: {!! json_encode($grafikPerbandingan['perangkat']) !!},
                    backgroundColor: colors.success,
                    borderColor: colors.success,
                    borderWidth: 1
                },
                {
                    label: 'Aset',
                    data: {!! json_encode($grafikPerbandingan['aset']) !!},
                    backgroundColor: colors.warning,
                    borderColor: colors.warning,
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Perbandingan Data Antar Desa'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Chart Klasifikasi Usia
    const klasifikasiUsiaCtx = document.getElementById('klasifikasiUsiaChart').getContext('2d');
    const klasifikasiUsiaChart = new Chart(klasifikasiUsiaCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($klasifikasiUsia->pluck('klasifikasi_usia')) !!},
            datasets: [{
                data: {!! json_encode($klasifikasiUsia->pluck('jumlah')) !!},
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.danger, colors.info],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Distribusi Penduduk Berdasarkan Usia'
                }
            }
        }
    });
    
    // Chart Pekerjaan
    const pekerjaanCtx = document.getElementById('pekerjaanChart').getContext('2d');
    const pekerjaanChart = new Chart(pekerjaanCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topPekerjaan->pluck('pekerjaan')) !!},
            datasets: [{
                label: 'Jumlah Penduduk',
                data: {!! json_encode($topPekerjaan->pluck('jumlah')) !!},
                backgroundColor: colors.info,
                borderColor: colors.info,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Pekerjaan Terbanyak'
                }
            }
        }
    });
    
    // Chart Pendidikan
    const pendidikanCtx = document.getElementById('pendidikanChart').getContext('2d');
    const pendidikanChart = new Chart(pendidikanCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($pendidikanData->pluck('pendidikan_terakhir')) !!},
            datasets: [{
                data: {!! json_encode($pendidikanData->pluck('jumlah')) !!},
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.danger, colors.info, colors.secondary, colors.dark],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Tingkat Pendidikan Penduduk'
                }
            }
        }
    });
    
    // Chart Aset
    const asetCtx = document.getElementById('asetChart').getContext('2d');
    const asetChart = new Chart(asetCtx, {
        type: 'pie',
        data: {
            labels: ['Aset Desa', 'Aset Tanah Warga'],
            datasets: [{
                data: [{{ $totalNilaiAsetDesa }}, {{ $totalNilaiAsetWarga }}],
                backgroundColor: [colors.warning, colors.danger],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                title: {
                    display: true,
                    text: 'Perbandingan Nilai Aset'
                }
            }
        }
    });
    
    // Export PDF
     document.getElementById('btn-export-pdf-penduduk').addEventListener('click', function() {
         const element = document.querySelector('.container-fluid');
         const opt = {
             margin: 10,
             filename: 'statistik-kecamatan.pdf',
             image: { type: 'jpeg', quality: 0.98 },
             html2canvas: { scale: 2 },
             jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
         };
         
         // Tampilkan loading spinner
         showLoading('Menghasilkan PDF...');
         
         // Generate PDF dengan promise
         html2pdf().set(opt).from(element).save()
             .then(() => {
                 // Sembunyikan loading setelah selesai
                 hideLoading();
             })
             .catch(error => {
                 console.error('PDF generation failed:', error);
                 hideLoading();
                 alert('Gagal menghasilkan PDF. Silakan coba lagi.');
             });
     });
     
     // Export PDF untuk jenis data tertentu
     document.getElementById('btn-export-pdf-penduduk').addEventListener('click', function() {
         showLoading('Menghasilkan PDF Data Penduduk...');
         const element = document.querySelector('#printArea');
         const opt = {
             margin: 10,
             filename: 'statistik-penduduk.pdf',
             image: { type: 'jpeg', quality: 0.98 },
             html2canvas: { scale: 2 },
             jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
         };
         
         html2pdf().set(opt).from(element).save()
             .then(() => hideLoading())
             .catch(error => {
                 console.error('PDF generation failed:', error);
                 hideLoading();
                 alert('Gagal menghasilkan PDF. Silakan coba lagi.');
             });
     });
     
     document.getElementById('btn-export-pdf-aset').addEventListener('click', function() {
         showLoading('Menghasilkan PDF Data Aset...');
         const element = document.querySelector('#printArea');
         const opt = {
             margin: 10,
             filename: 'statistik-aset.pdf',
             image: { type: 'jpeg', quality: 0.98 },
             html2canvas: { scale: 2 },
             jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
         };
         
         html2pdf().set(opt).from(element).save()
             .then(() => hideLoading())
             .catch(error => {
                 console.error('PDF generation failed:', error);
                 hideLoading();
                 alert('Gagal menghasilkan PDF. Silakan coba lagi.');
             });
     });
     
     document.getElementById('btn-export-pdf-perangkat').addEventListener('click', function() {
         showLoading('Menghasilkan PDF Data Perangkat...');
         const element = document.querySelector('#printArea');
         const opt = {
             margin: 10,
             filename: 'statistik-perangkat.pdf',
             image: { type: 'jpeg', quality: 0.98 },
             html2canvas: { scale: 2 },
             jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
         };
         
         html2pdf().set(opt).from(element).save()
             .then(() => hideLoading())
             .catch(error => {
                 console.error('PDF generation failed:', error);
                 hideLoading();
                 alert('Gagal menghasilkan PDF. Silakan coba lagi.');
             });
     });
     
     // Export Excel
     document.getElementById('btn-export-excel').addEventListener('click', function() {
         // Tampilkan loading spinner
         showLoading('Menghasilkan Excel...');
         
         // Persiapkan data untuk Excel
         const data = [];
         
         // Header
         data.push(['Desa', 'Total Penduduk', 'Laki-laki', 'Perempuan', 'Perangkat Desa', 'Jumlah Aset', 'Nilai Aset (Rp)', 'Status Update']);
         
         // Data desa
         @foreach($statistikPerDesa as $desa)
         data.push([
             '{{ $desa["nama_desa"] }}',
             {{ $desa["total_penduduk"] }},
             {{ $desa["penduduk_pria"] }},
             {{ $desa["penduduk_wanita"] }},
             {{ $desa["total_perangkat"] }},
             {{ $desa["total_aset"] }},
             {{ $desa["nilai_aset"] }},
             '{{ $desa["status_update"] == "hijau" ? "Terbaru" : ($desa["status_update"] == "kuning" ? "Perlu Update" : "Belum Update") }}'
         ]);
         @endforeach
         
         // Gunakan setTimeout untuk memberikan waktu bagi UI untuk menampilkan loading
         setTimeout(() => {
             try {
                 const wb = XLSX.utils.book_new();
                 const ws = XLSX.utils.aoa_to_sheet(data);
                 XLSX.utils.book_append_sheet(wb, ws, 'Statistik Desa');
                 
                 // Simpan file
                 XLSX.writeFile(wb, 'statistik-kecamatan.xlsx');
                 hideLoading();
             } catch (error) {
                 console.error('Excel generation failed:', error);
                 hideLoading();
                 alert('Gagal menghasilkan Excel. Silakan coba lagi.');
             }
         }, 500);
     });
     
     // Export Excel untuk jenis data tertentu
     const exportExcelData = function(jenis) {
         showLoading(`Menghasilkan Excel Data ${jenis}...`);
         
         const data = [];
         let header, filename;
         
         if (jenis === 'Penduduk') {
             header = ['Desa', 'Total Penduduk', 'Laki-laki', 'Perempuan'];
             filename = 'statistik-penduduk.xlsx';
             
             data.push(header);
             @foreach($statistikPerDesa as $desa)
             data.push([
                 '{{ $desa["nama_desa"] }}',
                 {{ $desa["total_penduduk"] }},
                 {{ $desa["penduduk_pria"] }},
                 {{ $desa["penduduk_wanita"] }}
             ]);
             @endforeach
         } else if (jenis === 'Aset') {
             header = ['Desa', 'Jumlah Aset', 'Nilai Aset (Rp)'];
             filename = 'statistik-aset.xlsx';
             
             data.push(header);
             @foreach($statistikPerDesa as $desa)
             data.push([
                 '{{ $desa["nama_desa"] }}',
                 {{ $desa["total_aset"] }},
                 {{ $desa["nilai_aset"] }}
             ]);
             @endforeach
         } else if (jenis === 'Perangkat') {
             header = ['Desa', 'Jumlah Perangkat'];
             filename = 'statistik-perangkat.xlsx';
             
             data.push(header);
             @foreach($statistikPerDesa as $desa)
             data.push([
                 '{{ $desa["nama_desa"] }}',
                 {{ $desa["total_perangkat"] }}
             ]);
             @endforeach
         }
         
         setTimeout(() => {
             try {
                 const wb = XLSX.utils.book_new();
                 const ws = XLSX.utils.aoa_to_sheet(data);
                 XLSX.utils.book_append_sheet(wb, ws, `Data ${jenis}`);
                 XLSX.writeFile(wb, filename);
                 hideLoading();
             } catch (error) {
                 console.error('Excel generation failed:', error);
                 hideLoading();
                 alert('Gagal menghasilkan Excel. Silakan coba lagi.');
             }
         }, 500);
     };
     
     document.getElementById('btn-export-excel-penduduk').addEventListener('click', function() {
         exportExcelData('Penduduk');
     });
     
     document.getElementById('btn-export-excel-aset').addEventListener('click', function() {
         exportExcelData('Aset');
     });
     
     document.getElementById('btn-export-excel-perangkat').addEventListener('click', function() {
         exportExcelData('Perangkat');
     });
});
</script>
@endpush