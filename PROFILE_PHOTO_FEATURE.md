# Fitur Foto Profil Admin

## Deskripsi

Fitur ini memungkinkan setiap admin (Admin Kecamatan dan Admin Desa) untuk memiliki foto profil yang dapat diupload, diedit, dan dihapus.

## Fitur yang Tersedia

### 1. Upload Foto Profil

-   Admin dapat mengupload foto profil dengan format JPG, PNG, GIF
-   Ukuran maksimal file: 2MB
-   Preview foto langsung tersedia saat memilih file
-   Foto disimpan di folder `public/uploads/profile-photos/`

### 2. Edit Foto Profil

-   Admin dapat mengganti foto profil yang sudah ada
-   Foto lama akan otomatis dihapus saat upload foto baru
-   Preview foto baru langsung ditampilkan

### 3. Hapus Foto Profil

-   Admin dapat menghapus foto profil yang sudah ada
-   Setelah dihapus, akan kembali ke avatar default
-   File foto akan dihapus dari server

### 4. Tampilan Foto Profil

-   Foto profil ditampilkan di sidebar layout admin
-   Foto profil ditampilkan di halaman detail user
-   Foto profil ditampilkan di tabel daftar user
-   Avatar default digunakan jika belum ada foto profil

## Implementasi Teknis

### Database

-   Kolom `profile_photo` ditambahkan ke tabel `users`
-   Tipe data: VARCHAR, nullable

### Model User

-   Field `profile_photo` ditambahkan ke `$fillable`
-   Accessor `getProfilePhotoUrlAttribute()` untuk mendapatkan URL foto

### Controller

-   **Admin\UserController**: Menangani CRUD foto profil untuk admin kecamatan
-   **AdminDesa\ProfileController**: Menangani foto profil untuk admin desa

### Routes

-   `POST /admin/users/{user}/remove-profile-photo` - Hapus foto profil (admin)
-   `POST /admin-desa/profile/remove-profile-photo` - Hapus foto profil (admin desa)

### Views

-   Form create/edit user dengan field upload foto
-   Form edit profil admin desa dengan field upload foto
-   Preview foto dan tombol hapus foto
-   Tampilan foto di sidebar dan tabel

## File yang Dimodifikasi

### Database

-   `database/migrations/2025_09_20_230226_add_profile_photo_to_users_table.php`

### Models

-   `app/Models/User.php`

### Controllers

-   `app/Http/Controllers/Admin/UserController.php`
-   `app/Http/Controllers/AdminDesa/ProfileController.php`

### Routes

-   `routes/web.php`

### Views

-   `resources/views/admin/users/create.blade.php`
-   `resources/views/admin/users/edit.blade.php`
-   `resources/views/admin/users/index.blade.php`
-   `resources/views/admin/users/show.blade.php`
-   `resources/views/admin-desa/profile/edit.blade.php`
-   `resources/views/layouts/admin.blade.php`
-   `resources/views/layouts/admin-desa.blade.php`

### Assets

-   `public/images/default-avatar.svg` - Avatar default
-   `public/uploads/profile-photos/` - Folder penyimpanan foto

## Cara Penggunaan

### Untuk Admin Kecamatan

1. Masuk ke menu "User Management"
2. Klik "Tambah User" atau "Edit" pada user yang ada
3. Upload foto profil di field "Upload Foto Profil"
4. Klik "Simpan" atau "Perbarui"

### Untuk Admin Desa

1. Klik "Edit Profil" di sidebar
2. Upload foto profil di field "Upload Foto Profil"
3. Klik "Simpan Perubahan"

### Menghapus Foto Profil

1. Di halaman edit user/profil, klik tombol "Hapus Foto"
2. Konfirmasi penghapusan
3. Foto akan dihapus dan kembali ke avatar default

## Keamanan

-   Validasi file upload (format dan ukuran)
-   File disimpan dengan nama unik untuk mencegah konflik
-   File lama dihapus otomatis saat upload file baru
-   Folder uploads ditambahkan ke .gitignore

## Catatan

-   Avatar default akan ditampilkan jika user belum memiliki foto profil
-   Foto profil akan otomatis di-resize dan di-crop menjadi bentuk lingkaran
-   Semua foto profil dapat diakses melalui URL publik
