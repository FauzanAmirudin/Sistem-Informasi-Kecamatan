<div align="center">

# 🏢 Sistem Informasi Kecamatan Belitang Jaya

[![Laravel](https://img.shields.io/badge/Laravel-v12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-v8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-v5.2-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

</div>

## 📋 Deskripsi Proyek

Sistem Informasi Kecamatan adalah platform digital yang dirancang untuk membantu pengelolaan data dan informasi di tingkat kecamatan dan desa. Sistem ini memungkinkan pengelolaan data penduduk, aset desa, dokumen, dan berbagai informasi penting lainnya secara terintegrasi.

Sistem ini memiliki dua tingkat akses utama:

-   **Admin Kecamatan**: Memiliki akses penuh untuk mengelola seluruh data kecamatan dan desa
-   **Admin Desa**: Memiliki akses untuk mengelola data desa masing-masing

## 🌟 Fitur Utama

-   👥 **Manajemen Data Penduduk**

    -   Pencatatan data penduduk lengkap
    -   Klasifikasi berdasarkan usia, jenis kelamin, dan status
    -   Pencarian dan filter data penduduk

-   👨‍💼 **Manajemen Perangkat Desa**

    -   Pencatatan data perangkat desa
    -   Pengelolaan jabatan dan masa tugas
    -   Riwayat perangkat desa

-   🏠 **Manajemen Aset Desa**

    -   Pencatatan aset desa (tanah, bangunan, inventaris)
    -   Pengelolaan kondisi dan nilai aset
    -   Riwayat aset desa

-   📄 **Manajemen Dokumen**

    -   Upload dan penyimpanan dokumen penting
    -   Kategorisasi dokumen
    -   Kontrol akses dokumen

-   📊 **Dashboard dan Statistik**

    -   Visualisasi data penduduk, perangkat, dan aset
    -   Laporan statistik desa
    -   Export data ke Excel dan PDF

-   🔍 **Monitoring Desa**
    -   Pemantauan status update data desa
    -   Notifikasi pembaruan data

## 🛠️ Teknologi yang Digunakan

### Backend

-   **[Laravel 12](https://laravel.com)**: Framework PHP untuk pengembangan aplikasi web
-   **[PHP 8.2](https://php.net)**: Bahasa pemrograman server-side
-   **[MySQL](https://mysql.com)**: Sistem manajemen database relasional

### Frontend

-   **[Bootstrap 5.2](https://getbootstrap.com)**: Framework CSS untuk desain responsif
-   **[Vite](https://vitejs.dev)**: Build tool untuk frontend
-   **[SASS](https://sass-lang.com)**: Preprocessor CSS
-   **[Font Awesome](https://fontawesome.com)**: Ikon dan font

### Package Utama

-   **[Laravel UI](https://github.com/laravel/ui)**: Package untuk autentikasi dan UI
-   **[Intervention Image](https://image.intervention.io)**: Manipulasi gambar
-   **[DomPDF](https://github.com/barryvdh/laravel-dompdf)**: Generasi file PDF
-   **[Maatwebsite Excel](https://laravel-excel.com)**: Export/import data Excel

## 📁 Struktur Proyek

```
sistem-kecamatan/
├── app/                  # Kode aplikasi utama
│   ├── Http/            # Controllers, Middleware, Requests
│   ├── Models/          # Model database
│   ├── Exports/         # Kelas untuk export data
│   └── Providers/       # Service providers
├── config/              # File konfigurasi
├── database/            # Migrasi dan seeders
│   ├── migrations/      # Skema database
│   └── seeders/         # Data awal
├── public/              # File publik (CSS, JS, gambar)
├── resources/           # View dan asset
│   ├── views/           # Template Blade
│   │   ├── admin/       # View untuk admin kecamatan
│   │   ├── admin-desa/  # View untuk admin desa
│   │   └── layouts/     # Layout template
│   ├── js/              # File JavaScript
│   └── sass/            # File SASS
├── routes/              # Definisi rute
└── storage/             # File yang diupload dan cache
```

## 📊 Struktur Database

Sistem ini menggunakan beberapa tabel utama:

-   **users**: Data pengguna sistem
-   **desas**: Data desa di kecamatan
-   **penduduks**: Data penduduk desa
-   **perangkat_desas**: Data perangkat desa
-   **aset_desas**: Data aset milik desa
-   **aset_tanah_wargas**: Data aset tanah milik warga
-   **dokumens**: Dokumen-dokumen penting desa
-   **activities**: Log aktivitas pengguna

## 🚀 Cara Menjalankan Proyek

### Prasyarat

-   PHP 8.2 atau lebih tinggi
-   Composer
-   MySQL
-   Node.js dan NPM

### Langkah Instalasi

1. **Clone repositori**

    ```bash
    git clone [url-repositori]
    cd sistem-kecamatan
    ```

2. **Instal dependensi PHP**

    ```bash
    composer install
    ```

3. **Instal dependensi JavaScript**

    ```bash
    npm install
    ```

4. **Salin file .env**

    ```bash
    cp .env.example .env
    ```

5. **Generate application key**

    ```bash
    php artisan key:generate
    ```

6. **Konfigurasi database di file .env**

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistem_kecamatan
    DB_USERNAME=root
    DB_PASSWORD=
    ```

7. **Jalankan migrasi dan seeder**

    ```bash
    php artisan migrate --seed
    ```

8. **Buat symbolic link untuk storage**

    ```bash
    php artisan storage:link
    ```

9. **Compile asset**

    ```bash
    npm run build
    ```

10. **Jalankan server development**

    ```bash
    php artisan serve
    ```

11. **Akses aplikasi**
    Buka browser dan akses `http://localhost:8000`

## 👥 Akses Sistem

Setelah menjalankan seeder, Anda dapat login dengan kredensial berikut:

### Admin Kecamatan

-   Email: admin@kecamatan.com
-   Password: password123

### Admin Desa

-   Email: admin.desa1@kecamatan.com
-   Password: password123

## 📝 Lisensi

Sistem Informasi Kecamatan adalah perangkat lunak open-source yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

## 📞 Kontak

Untuk pertanyaan dan dukungan, silakan hubungi:

-   Email: support@kecamatan.go.id
-   Telepon: (021) 1234567
