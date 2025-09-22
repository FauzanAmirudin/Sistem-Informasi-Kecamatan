<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Information;
use App\Models\User;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil admin kecamatan pertama
        $adminKecamatan = User::where('role', 'admin_kecamatan')->first();
        
        if (!$adminKecamatan) {
            $this->command->warn('Tidak ada admin kecamatan ditemukan. Silakan buat user admin kecamatan terlebih dahulu.');
            return;
        }

        $informations = [
            [
                'judul' => 'Pengumuman Pembangunan Jembatan Desa',
                'konten' => 'Dengan hormat kami sampaikan bahwa akan dilaksanakan pembangunan jembatan penghubung antar desa yang akan dimulai pada tanggal 1 Oktober 2024. Selama masa pembangunan, jalan akan ditutup sementara dan masyarakat dimohon untuk menggunakan jalur alternatif yang telah disediakan.

Untuk informasi lebih lanjut, silakan menghubungi kantor kecamatan atau menghubungi nomor telepon yang tertera di bawah ini.

Terima kasih atas perhatian dan kerjasamanya.',
                'kategori' => 'pengumuman',
                'status' => 'published',
                'created_by' => $adminKecamatan->id,
                'published_at' => now(),
            ],
            [
                'judul' => 'Informasi Program Bantuan Sosial',
                'konten' => 'Kami informasikan bahwa program bantuan sosial untuk masyarakat kurang mampu akan dibuka kembali pada bulan November 2024. Program ini meliputi:

1. Bantuan Langsung Tunai (BLT)
2. Bantuan Pangan Non Tunai (BPNT)
3. Program Keluarga Harapan (PKH)

Persyaratan dan pendaftaran dapat dilakukan di kantor desa masing-masing dengan membawa dokumen yang diperlukan.

Jadwal pendaftaran: 1-15 November 2024
Pengumuman hasil: 30 November 2024',
                'kategori' => 'informasi',
                'status' => 'published',
                'created_by' => $adminKecamatan->id,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Berita Acara Musyawarah Desa',
                'konten' => 'Pada hari Senin, 16 September 2024 telah dilaksanakan musyawarah desa yang membahas beberapa agenda penting:

1. Laporan pertanggungjawaban kepala desa periode 2023-2024
2. Pembahasan Rencana Kerja Pemerintah Desa (RKPD) tahun 2025
3. Penetapan anggaran desa untuk tahun 2025
4. Pembahasan program pembangunan infrastruktur desa

Musyawarah berlangsung dengan baik dan menghasilkan beberapa keputusan penting yang akan dilaksanakan dalam tahun 2025.',
                'kategori' => 'berita',
                'status' => 'published',
                'created_by' => $adminKecamatan->id,
                'published_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Draft: Panduan Pelayanan Administrasi Desa',
                'konten' => 'Draft panduan pelayanan administrasi desa sedang dalam tahap penyusunan. Panduan ini akan mencakup:

- Prosedur pembuatan surat keterangan
- Persyaratan administrasi kependudukan
- Tata cara pelayanan publik di desa
- Standar operasional prosedur (SOP) pelayanan

Draft ini masih dalam tahap review dan akan dipublikasikan setelah mendapat persetujuan dari pihak terkait.',
                'kategori' => 'informasi',
                'status' => 'draft',
                'created_by' => $adminKecamatan->id,
                'published_at' => null,
            ],
            [
                'judul' => 'Pengumuman Libur Nasional',
                'konten' => 'Mengingatkan kepada seluruh masyarakat bahwa pada tanggal 17 Agustus 2024 akan dilaksanakan upacara bendera dalam rangka memperingati Hari Kemerdekaan Republik Indonesia.

Seluruh kantor pemerintahan desa dan kecamatan akan tutup pada hari tersebut. Pelayanan publik akan dilanjutkan kembali pada hari kerja berikutnya.

Mari kita bersama-sama memperingati hari kemerdekaan dengan penuh khidmat dan semangat kebangsaan.',
                'kategori' => 'pengumuman',
                'status' => 'published',
                'created_by' => $adminKecamatan->id,
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($informations as $information) {
            Information::create($information);
        }

        $this->command->info('Data sample informasi berhasil dibuat!');
    }
}
