<div align="center">

# 🏢 Sistem Informasi Kecamatan Belitang Jaya

[![Laravel](https://img.shields.io/badge/Laravel-v12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.2-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Vite](https://img.shields.io/badge/Vite-Build-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

</div>

## 📋 Deskripsi Singkat

Aplikasi ini membantu pengelolaan data di tingkat kecamatan dan desa: penduduk, perangkat desa, aset desa, dokumen, serta dashboard statistik dan laporan. Sistem mendukung dua peran utama dengan batasan akses yang jelas.

-   **Admin Kecamatan**: Mengelola seluruh data lintas desa, monitoring, dan laporan.
-   **Admin Desa**: Mengelola data untuk desa miliknya saja.

## 🌟 Fitur Utama

-   **Manajemen Data Penduduk**

    -   CRUD lengkap, validasi, riwayat perubahan
    -   Klasifikasi usia otomatis, statistik gender, pekerjaan, pendidikan
    -   Import dari Excel (template tersedia), export ke Excel dan PDF

-   **Manajemen Perangkat Desa**

    -   CRUD, status aktif/nonaktif, riwayat perangkat
    -   Export ke Excel dan PDF

-   **Aset Desa & Aset Tanah Warga**

    -   Pencatatan aset, nilai aset, bukti aset (upload dokumen/gambar)
    -   Rekap nilai aset per desa, export Excel/PDF

-   **Dokumen Desa**

    -   Unggah dokumen penting (SK, profil desa, SPH, dsb.)
    -   Penyimpanan terstruktur pada `storage/app/public` (linked ke `public/storage`)

-   **Dashboard & Statistik**

    -   Ringkasan per desa (penduduk, perangkat, aset)
    -   Grafik perbandingan (Chart.js), tabel detail dengan pencarian
    -   Status update per desa: Terbaru, Perlu Update, Belum Update

-   **Autentikasi & Keamanan**
    -   Login, proteksi rute per peran, pengecekan kepemilikan desa
    -   Logging aktivitas login di `app/Listeners/LogSuccessfulLogin.php`

## 📦 Teknologi & Paket

-   Laravel 12, PHP 8.2, MySQL
-   Bootstrap 5.2, Font Awesome, Vite, SASS/Tailwind utilities
-   Maatwebsite/Laravel-Excel (import/export), barryvdh/laravel-dompdf (PDF)

## 🗂️ Struktur Folder Singkat

```
app/
  Http/Controllers/Admin/       # Modul Admin Kecamatan
  Http/Controllers/AdminDesa/   # Modul Admin Desa
  Exports/, Imports/            # Export & Import (Excel/PDF)
  Models/                       # Model (Penduduk, Desa, Aset, dsb.)
resources/views/
  admin/, admin-desa/, layouts/ # Blade templates
public/uploads/                 # Berkas terunggah (bukti aset, dokumen, foto)
```

## ✅ Prasyarat

-   PHP 8.2+
-   Composer
-   Node.js 18+ dan NPM
-   MySQL/MariaDB

## 🚀 Instalasi & Menjalankan

1. Clone & pasang dependensi

```bash
git clone <repo-url>
cd sistem-kecamatan
composer install
npm install
```

2. Env & key

```bash
cp .env.example .env
php artisan key:generate
```

3. Konfigurasi database (.env)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_kecamatan
DB_USERNAME=root
DB_PASSWORD=
```

4. Migrasi & seeding awal

```bash
php artisan migrate --seed
php artisan storage:link
```

5. Build asset

```bash
npm run build       # build production
# atau mode pengembangan
npm run dev         # dev server dengan HMR
npm run watch       # rebuild saat file berubah
```

6. Jalankan server

```bash
php artisan serve
# akses: http://localhost:8000
```

## 👥 Akun Contoh (hasil seeder)

-   **Admin Kecamatan**
    -   Email: `admin@kecamatan.com`
    -   Password: `password123`
-   **Admin Desa**
    -   Email: `admin.desa1@kecamatan.com`
    -   Password: `password123`

## 📤 Import/Export Data

-   Template impor tersedia di `public/templates/`
-   Export Excel/PDF tersedia pada halaman statistik dan modul data (Penduduk, Perangkat, Aset Desa, Aset Tanah Warga)

## 🧭 Hak Akses & Aturan

-   Admin Kecamatan: akses penuh semua desa
-   Admin Desa: hanya data dengan `desa_id` sama dengan user
-   Middleware & validasi rute mencegah akses lintas desa

## 🧰 Skrip NPM Berguna

```bash
npm run dev      # Vite dev server
npm run build    # Production build
npm run watch    # Watch & rebuild
```

## 🧪 Testing (opsional)

```bash
php artisan test
```

## 🔒 Catatan Keamanan

-   Pastikan folder `storage/` dan `bootstrap/cache/` writable
-   Gunakan kredensial database yang aman
-   Produksi: set `APP_DEBUG=false`, set `APP_URL`

## 🛡️ Backup & Berkas

-   Upload pengguna disimpan pada `public/uploads/`
-   Disarankan backup berkala untuk folder `storage/` dan database

## 📞 Dukungan

Pertanyaan/dukungan: buka issue di repo ini atau hubungi administrator proyek.

## 📝 Lisensi

Dirilis di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
