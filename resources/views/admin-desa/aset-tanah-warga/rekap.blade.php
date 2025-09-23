@extends('layouts.admin-desa')

@section('page-title', 'Rekap Aset Tanah Warga')
@section('page-subtitle', 'Rekap data aset tanah warga')

@section('page-actions')
<div class="btn-group" role="group">
    <a href="{{ route('admin-desa.aset-tanah-warga.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>
        Kembali
    </a>
    <a href="{{ route('admin-desa.aset-tanah-warga.exportRekapExcel') }}" class="btn btn-success">
        <i class="fas fa-file-excel me-1"></i>
        Export Excel
    </a>
    <a href="{{ route('admin-desa.aset-tanah-warga.exportRekapPdf') }}" class="btn btn-danger">
        <i class="fas fa-file-pdf me-1"></i>
        Export PDF
    </a>
</div>
@endsection

@section('admin-content')
<!-- Statistik Cards -->
<div class="row mb-4">
    <div class="col-md-3">
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
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Luas</h6>
                        <h2 class="mt-2 mb-0">{{ number_format($totalLuas, 2, ',', '.') }} m²</h2>
                    </div>
                    <div>
                        <i class="fas fa-ruler-combined fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Rata-rata Nilai/m²</h6>
                        <h2 class="mt-2 mb-0">Rp {{ number_format($rataRataNilaiPerMeter, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-chart-line fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Nilai Aset</h6>
                        <h2 class="mt-2 mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Distribusi Jenis Tanah</h5>
            </div>
            <div class="card-body">
                <canvas id="jenisTanahChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Distribusi Status Kepemilikan</h5>
            </div>
            <div class="card-body">
                <canvas id="statusKepemilikanChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Nilai Aset Tanah per Jenis</h5>
            </div>
            <div class="card-body">
                <canvas id="nilaiAsetChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rekap -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Rekap Aset Tanah Warga</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th rowspan="2" class="text-center align-middle">Jenis Tanah</th>
                        <th colspan="2" class="text-center">Jumlah</th>
                        <th colspan="2" class="text-center">Luas (m²)</th>
                        <th colspan="2" class="text-center">Nilai Total (Rp)</th>
                    </tr>
                    <tr>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">%</th>
                        <th class="text-center">Luas</th>
                        <th class="text-center">%</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapJenisTanah as $jenis => $data)
                    <tr>
                        <td>
                            @php
                                $jenisTanahLabel = '';
                                $badgeClass = '';
                                
                                switch($jenis) {
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
                        <td class="text-center">{{ $data['jumlah'] }}</td>
                        <td class="text-center">{{ number_format($data['persenJumlah'], 2, ',', '.') }}%</td>
                        <td class="text-end">{{ number_format($data['luas'], 2, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($data['persenLuas'], 2, ',', '.') }}%</td>
                        <td class="text-end">{{ number_format($data['nilai'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($data['persenNilai'], 2, ',', '.') }}%</td>
                    </tr>
                    @endforeach
                    <tr class="table-secondary fw-bold">
                        <td>Total</td>
                        <td class="text-center">{{ $totalAsetTanah }}</td>
                        <td class="text-center">100%</td>
                        <td class="text-end">{{ number_format($totalLuas, 2, ',', '.') }}</td>
                        <td class="text-center">100%</td>
                        <td class="text-end">{{ number_format($totalNilai, 0, ',', '.') }}</td>
                        <td class="text-center">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data untuk chart jenis tanah
        const ctxJenisTanah = document.getElementById('jenisTanahChart').getContext('2d');
        const jenisTanahChart = new Chart(ctxJenisTanah, {
            type: 'pie',
            data: {
                labels: {!! json_encode($chartJenisTanah['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartJenisTanah['data']) !!},
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
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Data untuk chart status kepemilikan
        const ctxStatusKepemilikan = document.getElementById('statusKepemilikanChart').getContext('2d');
        const statusKepemilikanChart = new Chart(ctxStatusKepemilikan, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartStatusKepemilikan['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartStatusKepemilikan['data']) !!},
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.7)',    // Milik Sendiri
                        'rgba(108, 117, 125, 0.7)',  // Warisan
                        'rgba(40, 167, 69, 0.7)',    // Hibah
                        'rgba(220, 53, 69, 0.7)'     // Jual Beli
                    ],
                    borderColor: [
                        'rgba(0, 123, 255, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Data untuk chart nilai aset
        const ctxNilaiAset = document.getElementById('nilaiAsetChart').getContext('2d');
        const nilaiAsetChart = new Chart(ctxNilaiAset, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartNilaiAset['labels']) !!},
                datasets: [{
                    label: 'Nilai Total (Juta Rupiah)',
                    data: {!! json_encode($chartNilaiAset['data']) !!},
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
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush