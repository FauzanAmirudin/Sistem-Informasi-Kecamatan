<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Aset Desa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            padding: 0;
        }
        .header h2 {
            font-size: 16px;
            margin: 5px 0 0;
            padding: 0;
        }
        .header p {
            margin: 5px 0 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ASET DESA</h1>
        <h2>{{ $desa ? $desa->nama_desa : 'Semua Desa' }}</h2>
        <p>Tanggal: {{ $tanggal }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="20%">Nama Aset</th>
                <th width="15%">Kategori</th>
                <th width="15%">Tanggal Perolehan</th>
                <th width="15%">Nilai Perolehan</th>
                <th width="15%">Nilai Sekarang</th>
                <th width="15%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @if(count($asetDesas) > 0)
                @php $no = 1; @endphp
                @foreach($asetDesas as $aset)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $aset->nama_aset }}</td>
                        <td>{{ $aset->kategori_aset }}</td>
                        <td>{{ $aset->tanggal_perolehan->format('d/m/Y') }}</td>
                        <td class="text-right">Rp {{ number_format($aset->nilai_perolehan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($aset->nilai_sekarang, 0, ',', '.') }}</td>
                        <td>{{ $aset->kondisi }}</td>
                    </tr>
                @endforeach
                
                <!-- Summary Row -->
                <tr>
                    <th colspan="4" class="text-right">Total Nilai Aset:</th>
                    <th class="text-right">Rp {{ number_format($asetDesas->sum('nilai_perolehan'), 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($asetDesas->sum('nilai_sekarang'), 0, ',', '.') }}</th>
                    <th></th>
                </tr>
            @else
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data aset</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Ringkasan Statistik -->
    <table>
        <thead>
            <tr>
                <th colspan="2">Ringkasan Aset</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="60%">Total Jumlah Aset</td>
                <td class="text-right">{{ count($asetDesas) }}</td>
            </tr>
            <tr>
                <td>Aset Kondisi Baik</td>
                <td class="text-right">{{ $asetDesas->where('kondisi', 'Baik')->count() }}</td>
            </tr>
            <tr>
                <td>Aset Kondisi Rusak Ringan</td>
                <td class="text-right">{{ $asetDesas->where('kondisi', 'Rusak Ringan')->count() }}</td>
            </tr>
            <tr>
                <td>Aset Kondisi Rusak Berat</td>
                <td class="text-right">{{ $asetDesas->where('kondisi', 'Rusak Berat')->count() }}</td>
            </tr>
            <tr>
                <td>Total Nilai Perolehan</td>
                <td class="text-right">Rp {{ number_format($asetDesas->sum('nilai_perolehan'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Nilai Sekarang</td>
                <td class="text-right">Rp {{ number_format($asetDesas->sum('nilai_sekarang'), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>