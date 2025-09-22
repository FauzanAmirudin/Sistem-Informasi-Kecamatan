<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penduduk;
use App\Models\PerangkatDesa;
use App\Models\AsetDesa;
use App\Models\AsetTanahWarga;
use App\Models\Desa;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil desa pertama atau buat desa sample
        $desa = Desa::first();
        if (!$desa) {
            $desa = Desa::create([
                'nama_desa' => 'Desa Sample',
                'kode_desa' => '001',
                'kecamatan' => 'Kecamatan Sample',
                'kabupaten' => 'Kabupaten Sample',
                'provinsi' => 'Provinsi Sample',
                'kode_pos' => '12345',
                'alamat_kantor' => 'Jl. Sample No. 1',
                'telepon' => '081234567890',
                'email' => 'desa@sample.com',
                'website' => 'www.desasample.com',
                'luas_wilayah' => 1000.50,
                'jumlah_dusun' => 3,
                'jumlah_rt' => 15,
                'jumlah_rw' => 5,
                'latitude' => -7.250445,
                'longitude' => 112.768845,
            ]);
        }

        // Buat user admin desa jika belum ada
        $adminDesa = User::where('role', 'admin_desa')->where('desa_id', $desa->id)->first();
        if (!$adminDesa) {
            $adminDesa = User::create([
                'name' => 'Admin Desa Sample',
                'email' => 'admin@desasample.com',
                'password' => bcrypt('password'),
                'role' => 'admin_desa',
                'desa_id' => $desa->id,
                'phone' => '081234567890',
                'address' => 'Jl. Sample No. 1',
                'is_active' => true,
            ]);
        }

        // Hapus data penduduk lama jika ada
        Penduduk::where('desa_id', $desa->id)->delete();

        // Data sample penduduk dengan variasi yang lebih baik
        $pendudukData = [
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123456',
                'nama_lengkap' => 'Ahmad Susanto',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-05-15',
                'agama' => 'Islam',
                'status_perkawinan' => 'Menikah',
                'pekerjaan' => 'Petani',
                'pendidikan_terakhir' => 'SMA',
                'alamat' => 'RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '2010-01-01',
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123457',
                'nama_lengkap' => 'Siti Aminah',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1995-08-20',
                'agama' => 'Islam',
                'status_perkawinan' => 'Menikah',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'pendidikan_terakhir' => 'SMP',
                'alamat' => 'RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '2013-01-01',
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123458',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1985-12-10',
                'agama' => 'Islam',
                'status_perkawinan' => 'Menikah',
                'pekerjaan' => 'Wiraswasta',
                'pendidikan_terakhir' => 'SMA',
                'alamat' => 'RT 02 RW 01',
                'rt' => '02',
                'rw' => '01',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '2005-01-01',
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123459',
                'nama_lengkap' => 'Maya Sari',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2000-03-25',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Menikah',
                'pekerjaan' => 'Pelajar',
                'pendidikan_terakhir' => 'SMA',
                'alamat' => 'RT 02 RW 01',
                'rt' => '02',
                'rw' => '01',
                'memiliki_ktp' => false,
                'tanggal_rekam_ktp' => null,
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123460',
                'nama_lengkap' => 'Rudi Hermawan',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1975-07-30',
                'agama' => 'Islam',
                'status_perkawinan' => 'Menikah',
                'pekerjaan' => 'PNS',
                'pendidikan_terakhir' => 'S1',
                'alamat' => 'RT 03 RW 02',
                'rt' => '03',
                'rw' => '02',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '1995-01-01',
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123461',
                'nama_lengkap' => 'Dewi Kartika',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1988-11-12',
                'agama' => 'Islam',
                'status_perkawinan' => 'Menikah',
                'pekerjaan' => 'Guru',
                'pendidikan_terakhir' => 'S1',
                'alamat' => 'RT 03 RW 02',
                'rt' => '03',
                'rw' => '02',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '2008-01-01',
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123462',
                'nama_lengkap' => 'Eko Prasetyo',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '1992-04-18',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Menikah',
                'pekerjaan' => 'Karyawan Swasta',
                'pendidikan_terakhir' => 'D3',
                'alamat' => 'RT 04 RW 02',
                'rt' => '04',
                'rw' => '02',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '2012-01-01',
            ],
            [
                'desa_id' => $desa->id,
                'nik' => '1234567890123463',
                'nama_lengkap' => 'Fitriani',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Solo',
                'tanggal_lahir' => '1996-09-05',
                'agama' => 'Islam',
                'status_perkawinan' => 'Belum Menikah',
                'pekerjaan' => 'Mahasiswa',
                'pendidikan_terakhir' => 'SMA',
                'alamat' => 'RT 04 RW 02',
                'rt' => '04',
                'rw' => '02',
                'memiliki_ktp' => true,
                'tanggal_rekam_ktp' => '2014-01-01',
            ],
        ];

        foreach ($pendudukData as $data) {
            Penduduk::create($data);
        }

        // Data sample perangkat desa
        $perangkatData = [
            [
                'desa_id' => $desa->id,
                'nama_lengkap' => 'Kepala Desa Sample',
                'jabatan' => 'Kepala Desa',
                'nik' => '1234567890123001',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1970-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'S1',
                'alamat' => 'Jl. Kantor Desa',
                'telepon' => '081234567001',
                'tanggal_mulai_jabatan' => '2020-01-01',
                'tanggal_selesai_jabatan' => '2025-12-31',
                'status' => 'Aktif',
            ],
            [
                'desa_id' => $desa->id,
                'nama_lengkap' => 'Sekretaris Desa',
                'jabatan' => 'Sekretaris Desa',
                'nik' => '1234567890123002',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1975-05-15',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'pendidikan_terakhir' => 'S1',
                'alamat' => 'Jl. Kantor Desa',
                'telepon' => '081234567002',
                'tanggal_mulai_jabatan' => '2020-01-01',
                'tanggal_selesai_jabatan' => '2025-12-31',
                'status' => 'Aktif',
            ],
        ];

        foreach ($perangkatData as $data) {
            PerangkatDesa::create($data);
        }

        // Data sample aset desa
        $asetDesaData = [
            [
                'desa_id' => $desa->id,
                'kategori_aset' => 'Bangunan',
                'nama_aset' => 'Kantor Desa',
                'deskripsi' => 'Bangunan kantor desa utama',
                'nilai_perolehan' => 500000000,
                'nilai_sekarang' => 450000000,
                'tanggal_perolehan' => '2020-01-01',
                'kondisi' => 'Baik',
                'lokasi' => 'Jl. Kantor Desa',
                'bukti_kepemilikan' => 'Sertifikat',
                'keterangan' => 'Aset utama desa',
                'is_current' => true,
            ],
            [
                'desa_id' => $desa->id,
                'kategori_aset' => 'Kendaraan',
                'nama_aset' => 'Mobil Operasional',
                'deskripsi' => 'Mobil untuk operasional desa',
                'nilai_perolehan' => 150000000,
                'nilai_sekarang' => 120000000,
                'tanggal_perolehan' => '2021-01-01',
                'kondisi' => 'Baik',
                'lokasi' => 'Kantor Desa',
                'bukti_kepemilikan' => 'STNK',
                'keterangan' => 'Kendaraan operasional',
                'is_current' => true,
            ],
        ];

        foreach ($asetDesaData as $data) {
            AsetDesa::create($data);
        }

        // Data sample aset tanah warga
        $asetWargaData = [
            [
                'desa_id' => $desa->id,
                'nama_pemilik' => 'Ahmad Susanto',
                'nik_pemilik' => '1234567890123456',
                'nomor_sph' => 'SPH001',
                'tanggal_sph' => '2020-01-01',
                'luas_tanah' => 500.00,
                'nilai_per_meter' => 100000,
                'lokasi' => 'RT 01 RW 01',
                'jenis_tanah' => 'Tanah Darat',
                'status_kepemilikan' => 'SHM',
                'tanggal_perolehan' => '2015-01-01',
                'bukti_kepemilikan' => 'Sertifikat',
                'keterangan' => 'Tanah tempat tinggal',
            ],
            [
                'desa_id' => $desa->id,
                'nama_pemilik' => 'Budi Santoso',
                'nik_pemilik' => '1234567890123458',
                'nomor_sph' => 'SPH002',
                'tanggal_sph' => '2020-01-01',
                'luas_tanah' => 300.00,
                'nilai_per_meter' => 120000,
                'lokasi' => 'RT 02 RW 01',
                'jenis_tanah' => 'Tanah Darat',
                'status_kepemilikan' => 'SHM',
                'tanggal_perolehan' => '2018-01-01',
                'bukti_kepemilikan' => 'Sertifikat',
                'keterangan' => 'Tanah tempat tinggal',
            ],
        ];

        foreach ($asetWargaData as $data) {
            AsetTanahWarga::create($data);
        }

        $this->command->info('Data sample berhasil dibuat!');
        $this->command->info('Desa: ' . $desa->nama_desa);
        $this->command->info('Admin Desa: ' . $adminDesa->name . ' (' . $adminDesa->email . ')');
        $this->command->info('Password: password');
    }
}