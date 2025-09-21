# Fitur Shortcut Edit Profile di Dropdown Konfigurasi

## Deskripsi

Fitur ini menambahkan shortcut "Edit Profil" di dropdown konfigurasi pada sidebar untuk memudahkan admin (baik admin kecamatan maupun admin desa) mengakses halaman edit profil mereka.

## Fitur yang Ditambahkan

### 1. Shortcut Edit Profil di Admin Kecamatan

-   **Lokasi**: Sidebar → Dropdown "Konfigurasi" → "Edit Profil"
-   **Route**: `/admin/profile`
-   **Icon**: `fas fa-user-edit`
-   **Fungsi**: Langsung mengarahkan ke halaman edit profil admin kecamatan

### 2. Shortcut Edit Profil di Admin Desa

-   **Lokasi**: Sidebar → Dropdown "Konfigurasi" → "Edit Profil"
-   **Route**: `/admin-desa/profile`
-   **Icon**: `fas fa-user-edit`
-   **Fungsi**: Langsung mengarahkan ke halaman edit profil admin desa

## Implementasi Teknis

### Controller Baru

-   **File**: `app/Http/Controllers/Admin/ProfileController.php`
-   **Fungsi**: Menangani CRUD profil admin kecamatan
-   **Methods**:
    -   `edit()` - Menampilkan form edit profil
    -   `update()` - Update profil dengan foto
    -   `showResetPasswordForm()` - Form reset password
    -   `resetPassword()` - Proses reset password
    -   `removeProfilePhoto()` - Hapus foto profil

### Routes Baru

```php
// Profile Admin Kecamatan
Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/reset-password', [Admin\ProfileController::class, 'showResetPasswordForm'])->name('profile.reset-password');
Route::put('/profile/reset-password', [Admin\ProfileController::class, 'resetPassword']);
Route::post('/profile/remove-profile-photo', [Admin\ProfileController::class, 'removeProfilePhoto'])->name('profile.remove-profile-photo');
```

### Views Baru

-   **File**: `resources/views/admin/profile/edit.blade.php`

    -   Form edit profil dengan upload foto
    -   Preview foto dan tombol hapus
    -   Informasi akun di sidebar
    -   Link ke reset password

-   **File**: `resources/views/admin/profile/reset-password.blade.php`
    -   Form reset password
    -   Validasi password lama dan baru
    -   Toggle visibility password

### Layout Updates

-   **File**: `resources/views/layouts/admin.blade.php`

    -   Menambahkan menu "Edit Profil" di dropdown konfigurasi
    -   Update kondisi active state untuk route profile

-   **File**: `resources/views/layouts/admin-desa.blade.php`
    -   Menambahkan menu "Edit Profil" di dropdown konfigurasi
    -   Update kondisi active state untuk route profile

## Struktur Menu Dropdown Konfigurasi

### Admin Kecamatan

```
Konfigurasi
├── Edit Profil (NEW)
├── Dokumen & Bantuan
└── Manajemen User
```

### Admin Desa

```
Konfigurasi
├── Edit Profil (NEW)
└── Dokumen
```

## Fitur yang Tersedia di Halaman Edit Profil

### Admin Kecamatan

1. **Upload/Edit Foto Profil**

    - Upload foto baru
    - Preview foto
    - Hapus foto profil
    - Validasi format dan ukuran file

2. **Edit Informasi Pribadi**

    - Nama lengkap
    - Email
    - Nomor telepon
    - Alamat

3. **Reset Password**

    - Link ke halaman reset password
    - Validasi password lama
    - Konfirmasi password baru

4. **Informasi Akun**
    - Role (Admin Kecamatan)
    - Tanggal terdaftar
    - Terakhir diperbarui

### Admin Desa

1. **Upload/Edit Foto Profil**

    - Upload foto baru
    - Preview foto
    - Hapus foto profil
    - Validasi format dan ukuran file

2. **Edit Informasi Pribadi**

    - Nama lengkap
    - Email
    - Nomor telepon
    - Alamat

3. **Reset Password**

    - Link ke halaman reset password
    - Validasi password lama
    - Konfirmasi password baru

4. **Informasi Akun**
    - Role (Admin Desa)
    - Desa yang ditugaskan
    - Tanggal terdaftar
    - Terakhir diperbarui

## Cara Penggunaan

### Mengakses Edit Profil

1. **Admin Kecamatan**:

    - Klik dropdown "Konfigurasi" di sidebar
    - Klik "Edit Profil"
    - Atau langsung klik tombol "Edit Profil" di sidebar user info

2. **Admin Desa**:
    - Klik dropdown "Konfigurasi" di sidebar
    - Klik "Edit Profil"
    - Atau langsung klik tombol "Edit Profil" di sidebar user info

### Edit Profil

1. Upload foto profil (opsional)
2. Edit informasi pribadi
3. Klik "Simpan Perubahan"

### Reset Password

1. Di halaman edit profil, klik "Ubah Password"
2. Masukkan password lama
3. Masukkan password baru
4. Konfirmasi password baru
5. Klik "Ubah Password"

## Keamanan

-   Validasi file upload (format dan ukuran)
-   Validasi password lama sebelum reset
-   CSRF protection pada semua form
-   File foto disimpan dengan nama unik

## File yang Dimodifikasi/Ditambahkan

### Baru

-   `app/Http/Controllers/Admin/ProfileController.php`
-   `resources/views/admin/profile/edit.blade.php`
-   `resources/views/admin/profile/reset-password.blade.php`

### Dimodifikasi

-   `routes/web.php`
-   `resources/views/layouts/admin.blade.php`
-   `resources/views/layouts/admin-desa.blade.php`

## Catatan

-   Shortcut edit profil tersedia di kedua role admin
-   Menu akan aktif (highlighted) saat berada di halaman profile
-   Dropdown konfigurasi akan terbuka otomatis saat mengakses halaman profile
-   Semua fitur foto profil yang sudah ada tetap berfungsi
