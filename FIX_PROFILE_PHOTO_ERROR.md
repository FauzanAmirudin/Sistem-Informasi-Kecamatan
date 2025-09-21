# Perbaikan Error "Column not found: profile_photo"

## Deskripsi Error

Error yang terjadi:

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'profile_photo' in 'field list'
```

## Penyebab Error

Migration untuk menambahkan kolom `profile_photo` ke tabel `users` belum dijalankan, sehingga kolom tersebut tidak ada di database.

## Solusi yang Diterapkan

### 1. Pengecekan Status Migration

```bash
php artisan migrate:status
```

Hasil menunjukkan bahwa migration `2025_09_20_230226_add_profile_photo_to_users_table` masih dalam status "Pending".

### 2. Menjalankan Migration

```bash
php artisan migrate
```

Migration berhasil dijalankan dan kolom `profile_photo` ditambahkan ke tabel `users`.

### 3. Verifikasi Migration

```bash
php artisan migrate:status
```

Konfirmasi bahwa migration sudah berstatus "Ran" di batch [6].

### 4. Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Membersihkan semua cache untuk memastikan tidak ada masalah cache.

## Hasil Perbaikan

### Sebelum Perbaikan

-   Migration `add_profile_photo_to_users_table` status: **Pending**
-   Kolom `profile_photo` tidak ada di tabel `users`
-   Error saat mencoba update profil dengan foto

### Setelah Perbaikan

-   Migration `add_profile_photo_to_users_table` status: **Ran** (batch [6])
-   Kolom `profile_photo` sudah ada di tabel `users`
-   Fitur upload foto profil berfungsi normal

## Struktur Database yang Diperbaiki

### Tabel `users` (Setelah Migration)

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin_kecamatan', 'admin_desa') NOT NULL,
    desa_id BIGINT UNSIGNED NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    profile_photo VARCHAR(255) NULL,  -- KOLOM BARU
    is_active BOOLEAN DEFAULT TRUE,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (desa_id) REFERENCES desas(id) ON DELETE SET NULL
);
```

## Fitur yang Sekarang Berfungsi

### 1. Upload Foto Profil

-   Admin kecamatan dan admin desa dapat upload foto profil
-   Validasi format file (JPG, PNG, GIF)
-   Validasi ukuran file (maksimal 2MB)
-   Preview foto real-time

### 2. Edit Foto Profil

-   Ganti foto profil yang sudah ada
-   File lama otomatis dihapus
-   Preview foto baru

### 3. Hapus Foto Profil

-   Tombol hapus dengan konfirmasi
-   Kembali ke avatar default
-   File dihapus dari server

### 4. Tampilan Foto Profil

-   Di sidebar layout (admin kecamatan & admin desa)
-   Di tabel daftar user
-   Di halaman detail user
-   Bentuk lingkaran dengan object-fit cover

## Cara Testing

### 1. Akses Edit Profil

-   **Admin Kecamatan**: Sidebar → Konfigurasi → Edit Profil
-   **Admin Desa**: Sidebar → Konfigurasi → Edit Profil

### 2. Upload Foto Profil

1. Klik "Choose File" di field "Upload Foto Profil"
2. Pilih file gambar (JPG, PNG, GIF)
3. Lihat preview foto
4. Klik "Simpan Perubahan"

### 3. Edit Informasi Lain

1. Edit nama, email, telepon, atau alamat
2. Klik "Simpan Perubahan"
3. Verifikasi perubahan tersimpan

### 4. Hapus Foto Profil

1. Jika sudah ada foto, klik tombol "Hapus Foto"
2. Konfirmasi penghapusan
3. Verifikasi kembali ke avatar default

## File yang Terlibat

### Database

-   `database/migrations/2025_09_20_230226_add_profile_photo_to_users_table.php`

### Model

-   `app/Models/User.php` (field `profile_photo` di fillable)

### Controllers

-   `app/Http/Controllers/Admin/ProfileController.php`
-   `app/Http/Controllers/AdminDesa/ProfileController.php`

### Views

-   `resources/views/admin/profile/edit.blade.php`
-   `resources/views/admin-desa/profile/edit.blade.php`

## Catatan Penting

-   Migration harus dijalankan sebelum menggunakan fitur foto profil
-   File foto disimpan di `public/uploads/profile-photos/`
-   Avatar default tersedia di `public/images/default-avatar.svg`
-   Semua cache harus dibersihkan setelah migration

## Status: ✅ RESOLVED

Error "Column not found: profile_photo" telah berhasil diperbaiki dan fitur foto profil admin sekarang berfungsi dengan baik.
