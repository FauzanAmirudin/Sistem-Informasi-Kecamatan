<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Data Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 14px;
            margin: 5px 0;
            font-weight: normal;
        }
        
        .header p {
            font-size: 10px;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            border: 1px solid #333;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }
        
        td {
            font-size: 9px;
            min-height: 20px;
        }
        
        .no-col {
            width: 4%;
            text-align: center;
        }
        
        .nik-col {
            width: 12%;
        }
        
        .nama-col {
            width: 15%;
        }
        
        .jk-col {
            width: 6%;
            text-align: center;
        }
        
        .tempat-lahir-col {
            width: 10%;
        }
        
        .tanggal-lahir-col {
            width: 8%;
            text-align: center;
        }
        
        .usia-col {
            width: 5%;
            text-align: center;
        }
        
        .agama-col {
            width: 8%;
        }
        
        .status-col {
            width: 8%;
        }
        
        .pekerjaan-col {
            width: 10%;
        }
        
        .pendidikan-col {
            width: 8%;
        }
        
        .alamat-col {
            width: 12%;
        }
        
        .rt-col {
            width: 4%;
            text-align: center;
        }
        
        .rw-col {
            width: 4%;
            text-align: center;
        }
        
        .ktp-col {
            width: 6%;
            text-align: center;
        }
        
        .footer {
            margin-top: 20px;
            font-size: 8px;
            text-align: center;
            color: #666;
        }
        
        .instruksi {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        .instruksi h3 {
            margin: 0 0 5px 0;
            font-size: 10px;
            font-weight: bold;
        }
        
        .instruksi ul {
            margin: 5px 0;
            padding-left: 15px;
        }
        
        .instruksi li {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>TEMPLATE DATA PENDUDUK</h1>
        <h2>Formulir Pengisian Data Penduduk</h2>
        <p>Tanggal: {{ $tanggal }}</p>
    </div>

    <div class="instruksi">
        <h3>Petunjuk Pengisian:</h3>
        <ul>
            <li>Isi data penduduk sesuai dengan kolom yang tersedia</li>
            <li>NIK harus 16 digit angka</li>
            <li>Tanggal lahir format: DD/MM/YYYY</li>
            <li>Jenis kelamin: L (Laki-laki) atau P (Perempuan)</li>
            <li>Status perkawinan: Belum Kawin, Kawin, Cerai Hidup, Cerai Mati</li>
            <li>Pendidikan: Tidak Sekolah, SD, SMP, SMA, D3, S1, S2, S3</li>
            <li>Memiliki KTP: Ya atau Tidak</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th class="nik-col">NIK</th>
                <th class="nama-col">Nama Lengkap</th>
                <th class="jk-col">J/K</th>
                <th class="tempat-lahir-col">Tempat Lahir</th>
                <th class="tanggal-lahir-col">Tanggal Lahir</th>
                <th class="usia-col">Usia</th>
                <th class="agama-col">Agama</th>
                <th class="status-col">Status Kawin</th>
                <th class="pekerjaan-col">Pekerjaan</th>
                <th class="pendidikan-col">Pendidikan</th>
                <th class="alamat-col">Alamat</th>
                <th class="rt-col">RT</th>
                <th class="rw-col">RW</th>
                <th class="ktp-col">KTP</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 1; $i <= $jumlahBaris; $i++)
            <tr>
                <td class="no-col">{{ $i }}</td>
                <td class="nik-col"></td>
                <td class="nama-col"></td>
                <td class="jk-col"></td>
                <td class="tempat-lahir-col"></td>
                <td class="tanggal-lahir-col"></td>
                <td class="usia-col"></td>
                <td class="agama-col"></td>
                <td class="status-col"></td>
                <td class="pekerjaan-col"></td>
                <td class="pendidikan-col"></td>
                <td class="alamat-col"></td>
                <td class="rt-col"></td>
                <td class="rw-col"></td>
                <td class="ktp-col"></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <div class="footer">
        <p>Template ini dapat digunakan untuk mengumpulkan data penduduk secara manual</p>
        <p>Setelah diisi, data dapat dimasukkan ke dalam sistem melalui fitur import</p>
    </div>
</body>
</html>
