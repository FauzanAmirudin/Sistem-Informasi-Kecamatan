<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerangkatDesa;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PerangkatDesaSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if ($user) {
            Auth::login($user); // 🔑 supaya activity() tahu siapa user_id
        }
        // Ambil semua desa
        $desas = Desa::all();
        
        // Data jabatan perangkat desa
        $jabatan = [
            'Sekretaris Desa', 'Kepala Urusan Keuangan', 'Kepala Urusan Umum', 
            'Kepala Seksi Pemerintahan', 'Kepala Seksi Kesejahteraan', 'Kepala Seksi Pelayanan', 
            'Kepala Dusun 1', 'Kepala Dusun 2', 'Kepala Dusun 3', 'Staf Administrasi', 
            'Operator Desa', 'Bendahara Desa'
        ];
        
        // Data pendidikan terakhir
        $pendidikan = ['SMA', 'D3', 'S1', 'S2'];
        
        // Buat data perangkat desa untuk setiap desa
        foreach ($desas as $desa) {
            // Buat 5-8 perangkat desa per desa
            $jumlahPerangkat = rand(5, 8);
            $jabatanTerpakai = [];
            
            for ($i = 0; $i < $jumlahPerangkat; $i++) {
                // Pilih jabatan yang belum terpakai
                $jabatanTersedia = array_diff(array_keys($jabatan), $jabatanTerpakai);
                if (empty($jabatanTersedia)) {
                    break; // Semua jabatan sudah terpakai
                }
                
                $indexJabatan = $jabatanTersedia[array_rand($jabatanTersedia)];
                $jabatanTerpakai[] = $indexJabatan;
                $namaJabatan = $jabatan[$indexJabatan];
                
                // Tentukan jenis kelamin secara acak
                $jenisKelamin = rand(0, 1) ? 'L' : 'P';
                
                // Tentukan tanggal lahir (25-55 tahun yang lalu)
                $tahunLahir = Carbon::now()->subYears(rand(25, 55))->year;
                $bulanLahir = rand(1, 12);
                $hariLahir = rand(1, 28);
                $tanggalLahir = Carbon::create($tahunLahir, $bulanLahir, $hariLahir);
                
                // Tentukan tanggal mulai tugas (1-5 tahun yang lalu)
                $tanggalMulaiTugas = Carbon::now()->subYears(rand(1, 5))->subMonths(rand(0, 11))->subDays(rand(0, 30));
                
                // Tentukan tanggal akhir tugas (20% kemungkinan sudah ada tanggal akhir)
                $tanggalAkhirTugas = null;
                if (rand(1, 10) <= 2) {
                    $tanggalAkhirTugas = Carbon::now()->addYears(rand(1, 5))->addMonths(rand(0, 11))->addDays(rand(0, 30));
                }
                
                // Tentukan pendidikan terakhir
                $pendidikanTerakhir = $pendidikan[array_rand($pendidikan)];
                
                // Buat NIK unik
                $nik = '1' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) . 
                       str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT) . 
                       rand(1, 9);
                
                // Buat data perangkat desa
                PerangkatDesa::create([
                    'desa_id' => $desa->id,
                    'nama_lengkap' => $this->generateNama($jenisKelamin),
                    'jabatan' => $namaJabatan,
                    'nik' => $nik,
                    'tempat_lahir' => $this->generateTempatLahir(),
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => $jenisKelamin,
                    'pendidikan_terakhir' => $pendidikanTerakhir,
                    'alamat' => 'Dusun ' . rand(1, 5) . ', ' . $desa->nama_desa,
                    'no_telepon' => '08' . rand(1, 9) . rand(10000000, 99999999),
                    'tanggal_mulai_tugas' => $tanggalMulaiTugas,
                    'tanggal_akhir_tugas' => $tanggalAkhirTugas,
                    'sk_pengangkatan' => null, // Tidak ada file dalam seeder
                    'jobdesk' => $this->generateJobdesk($namaJabatan),
                    'status' => 'aktif',
                    'is_current' => true,
                    'updated_by' => $user?->id ?? 1, // fallback ke ID 1
                ]);
            }
        }
    }
    
    // Helper untuk generate nama acak
    private function generateNama($jenisKelamin)
    {
        $namaDepanLaki = [
            'Budi', 'Ahmad', 'Dedi', 'Eko', 'Faisal', 'Gunawan', 'Hendra', 'Irfan', 
            'Joko', 'Kurniawan', 'Lukman', 'Muhammad', 'Nanda', 'Oki', 'Purnomo', 
            'Rudi', 'Santoso', 'Tono', 'Umar', 'Wahyu', 'Yusuf', 'Zaenal'
        ];
        
        $namaDepanPerempuan = [
            'Ani', 'Bintang', 'Citra', 'Dewi', 'Endang', 'Fitri', 'Gita', 'Hesti', 
            'Indah', 'Juwita', 'Kartika', 'Lestari', 'Mawar', 'Nita', 'Oktavia', 
            'Putri', 'Ratna', 'Sari', 'Tari', 'Utami', 'Vina', 'Wati', 'Yanti', 'Zahra'
        ];
        
        $namaBelakang = [
            'Saputra', 'Wijaya', 'Kusuma', 'Hidayat', 'Nugraha', 'Pratama', 'Putra', 
            'Susanto', 'Santoso', 'Wibowo', 'Setiawan', 'Suryadi', 'Ramadhan', 'Permana', 
            'Kurniawan', 'Hartono', 'Gunawan', 'Firmansyah', 'Pradana', 'Utama'
        ];
        
        if ($jenisKelamin == 'L') {
            $namaDepan = $namaDepanLaki[array_rand($namaDepanLaki)];
        } else {
            $namaDepan = $namaDepanPerempuan[array_rand($namaDepanPerempuan)];
        }
        
        // 80% kemungkinan memiliki nama belakang
        if (rand(1, 10) <= 8) {
            return $namaDepan . ' ' . $namaBelakang[array_rand($namaBelakang)];
        } else {
            return $namaDepan;
        }
    }
    
    // Helper untuk generate tempat lahir acak
    private function generateTempatLahir()
    {
        $kotaLahir = [
            'Palembang', 'Prabumulih', 'Lubuk Linggau', 'Baturaja', 'Lahat', 'Pagaralam', 
            'Muara Enim', 'Kayu Agung', 'Indralaya', 'Martapura', 'Belitang', 'Muaradua'
        ];
        
        return $kotaLahir[array_rand($kotaLahir)];
    }
    
    // Helper untuk generate jobdesk berdasarkan jabatan
    private function generateJobdesk($jabatan)
    {
        $jobdeskSekretaris = [
            'Membantu Kepala Desa dalam bidang administrasi pemerintahan',
            'Melaksanakan urusan ketatausahaan, surat menyurat, dan arsip',
            'Melaksanakan urusan umum, perencanaan, dan pelaporan',
            'Menyusun dan melaksanakan prosedur dan standar pelayanan kantor desa'
        ];
        
        $jobdeskKeuangan = [
            'Melaksanakan pengelolaan administrasi keuangan desa',
            'Menyusun laporan pertanggungjawaban keuangan desa',
            'Membantu Kepala Desa dalam melaksanakan pengelolaan keuangan desa',
            'Melakukan verifikasi dan validasi keuangan desa'
        ];
        
        $jobdeskUmum = [
            'Melaksanakan urusan ketatausahaan dan umum',
            'Melaksanakan urusan perlengkapan, aset, dan inventarisasi',
            'Melaksanakan urusan rumah tangga kantor',
            'Mengelola administrasi surat menyurat'
        ];
        
        $jobdeskPemerintahan = [
            'Melaksanakan manajemen pemerintahan desa',
            'Menyusun rancangan regulasi desa',
            'Melakukan pembinaan wilayah dan masyarakat',
            'Mengelola administrasi kependudukan'
        ];
        
        $jobdeskKesejahteraan = [
            'Melaksanakan pembangunan sarana prasarana desa',
            'Melaksanakan pembinaan kemasyarakatan desa',
            'Melaksanakan pemberdayaan masyarakat dan desa',
            'Mengelola program kesejahteraan masyarakat'
        ];
        
        $jobdeskPelayanan = [
            'Melaksanakan penyuluhan dan motivasi terhadap pelaksanaan hak dan kewajiban masyarakat',
            'Meningkatkan upaya partisipasi masyarakat',
            'Melaksanakan pelestarian nilai sosial budaya masyarakat',
            'Melaksanakan pelayanan kepada masyarakat'
        ];
        
        $jobdeskKepDusun = [
            'Membantu pelaksanaan tugas Kepala Desa di wilayah dusun',
            'Melaksanakan kegiatan pemerintahan, pembangunan, dan kemasyarakatan di dusun',
            'Melaksanakan keputusan dan kebijakan Kepala Desa',
            'Membina dan meningkatkan swadaya gotong royong'
        ];
        
        $jobdeskStaf = [
            'Membantu administrasi pemerintahan desa',
            'Melaksanakan pelayanan administrasi kepada masyarakat',
            'Membantu pengelolaan arsip dan dokumentasi desa',
            'Membantu persiapan rapat dan notulensi'
        ];
        
        $jobdeskOperator = [
            'Mengelola sistem informasi desa',
            'Melaksanakan entry data dan pengelolaan website desa',
            'Membantu pengelolaan administrasi berbasis teknologi informasi',
            'Memelihara perangkat teknologi informasi desa'
        ];
        
        $jobdeskBendahara = [
            'Menerima, menyimpan, menyetorkan, menatausahakan, dan mempertanggungjawabkan keuangan desa',
            'Melakukan pencatatan setiap penerimaan dan pengeluaran',
            'Melakukan tutup buku setiap akhir bulan',
            'Mempertanggungjawabkan uang melalui laporan pertanggungjawaban'
        ];
        
        if (strpos($jabatan, 'Sekretaris') !== false) {
            return $jobdeskSekretaris[array_rand($jobdeskSekretaris)];
        } elseif (strpos($jabatan, 'Keuangan') !== false) {
            return $jobdeskKeuangan[array_rand($jobdeskKeuangan)];
        } elseif (strpos($jabatan, 'Umum') !== false) {
            return $jobdeskUmum[array_rand($jobdeskUmum)];
        } elseif (strpos($jabatan, 'Pemerintahan') !== false) {
            return $jobdeskPemerintahan[array_rand($jobdeskPemerintahan)];
        } elseif (strpos($jabatan, 'Kesejahteraan') !== false) {
            return $jobdeskKesejahteraan[array_rand($jobdeskKesejahteraan)];
        } elseif (strpos($jabatan, 'Pelayanan') !== false) {
            return $jobdeskPelayanan[array_rand($jobdeskPelayanan)];
        } elseif (strpos($jabatan, 'Dusun') !== false) {
            return $jobdeskKepDusun[array_rand($jobdeskKepDusun)];
        } elseif (strpos($jabatan, 'Staf') !== false) {
            return $jobdeskStaf[array_rand($jobdeskStaf)];
        } elseif (strpos($jabatan, 'Operator') !== false) {
            return $jobdeskOperator[array_rand($jobdeskOperator)];
        } elseif (strpos($jabatan, 'Bendahara') !== false) {
            return $jobdeskBendahara[array_rand($jobdeskBendahara)];
        } else {
            return 'Melaksanakan tugas sesuai dengan jabatan dan arahan Kepala Desa';
        }
    }
}