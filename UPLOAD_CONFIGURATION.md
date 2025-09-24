# Konfigurasi File Upload untuk Hosting

## Perubahan yang Dilakukan

Sistem telah dikonfigurasi untuk menyimpan file upload langsung ke direktori `public/uploads` agar file dapat diakses langsung setelah hosting tanpa perlu symlink.

### 1. Konfigurasi Filesystem

File `config/filesystems.php` telah dikonfigurasi dengan disk `uploads`:

```php
'uploads' => [
    'driver' => 'local',
    'root' => public_path('uploads'),
    'url' => env('APP_URL').'/uploads',
    'visibility' => 'public',
    'throw' => false,
    'report' => false,
],
```

### 2. Struktur Direktori

Direktori `public/uploads` telah dibuat dengan subdirektori berikut:

-   `dokumen/` - Untuk dokumen umum
-   `bukti-aset/` - Untuk bukti kepemilikan aset desa
-   `aset-tanah-warga/` - Untuk bukti kepemilikan aset tanah warga
-   `sk-perangkat/` - Untuk SK pengangkatan perangkat desa
-   `sk-kepala-desa/` - Untuk SK kepala desa
-   `monografi/` - Untuk file monografi desa
-   `profile-photos/` - Untuk foto profil user

### 3. Controller yang Diubah

Semua controller yang menggunakan file upload telah diubah untuk menggunakan disk `uploads`:

-   `app/Http/Controllers/Admin/DokumenController.php`
-   `app/Http/Controllers/AdminDesa/DokumenController.php`
-   `app/Http/Controllers/Admin/AsetDesaController.php`
-   `app/Http/Controllers/AdminDesa/AsetDesaController.php`
-   `app/Http/Controllers/Admin/AsetTanahWargaController.php`
-   `app/Http/Controllers/AdminDesa/AsetTanahWargaController.php`
-   `app/Http/Controllers/Admin/ProfileController.php`
-   `app/Http/Controllers/AdminDesa/ProfileController.php`
-   `app/Http/Controllers/Admin/UserController.php`
-   `app/Http/Controllers/AdminDesa/ProfilDesaController.php`
-   `app/Http/Controllers/Admin/PerangkatDesaController.php`
-   `app/Http/Controllers/AdminDesa/PerangkatDesaController.php`
-   `app/Http/Controllers/Admin/DesaController.php`
-   `app/Http/Controllers/AdminDesa/DesaController.php`

### 4. Model yang Diubah

Model yang menggunakan accessor untuk file URL telah diubah:

-   `app/Models/AsetDesa.php`
-   `app/Models/AsetTanahWarga.php`
-   `app/Models/Dokumen.php`
-   `app/Models/PerangkatDesa.php`
-   `app/Models/User.php`

### 5. Helper yang Diubah

File `app/Helpers/FileHelper.php` telah diubah untuk menggunakan disk `uploads`.

### 6. View yang Diubah

View yang menggunakan URL file telah diubah:

-   `resources/views/admin-desa/aset-desa/show.blade.php`
-   `resources/views/admin-desa/aset-tanah-warga/show.blade.php`
-   `resources/views/admin-desa/aset-tanah-warga/edit.blade.php`

### 7. File Konfigurasi

File `.htaccess` telah dibuat di `public/uploads/` untuk:

-   Mengizinkan akses ke file upload
-   Mencegah akses ke file PHP
-   Mengatur MIME type yang benar
-   Mengaktifkan kompresi
-   Mengatur cache headers

## Cara Kerja

1. **Upload File**: File diupload menggunakan `Storage::disk('uploads')` yang menyimpan file langsung ke `public/uploads/`
2. **Akses File**: File dapat diakses langsung melalui URL `https://belitangjaya.devzoneweb.com/uploads/...`
3. **Download File**: File didownload menggunakan `public_path('uploads/' . $filePath)`

## Keuntungan

1. **Tidak Perlu Symlink**: File dapat diakses langsung tanpa perlu membuat symlink
2. **Kompatibel dengan Hosting**: Bekerja dengan semua jenis hosting yang mendukung PHP
3. **Performa Lebih Baik**: File diakses langsung tanpa melalui Laravel routing
4. **Cache Headers**: File memiliki cache headers yang sesuai untuk performa yang lebih baik

## Catatan Penting

-   Pastikan direktori `public/uploads` memiliki permission yang sesuai (755)
-   File yang sudah ada di `storage/app/public` perlu dipindahkan ke `public/uploads` jika diperlukan
-   Backup data sebelum melakukan perubahan ini
