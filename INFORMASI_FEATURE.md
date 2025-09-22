# Fitur Informasi & Pengumuman

## Deskripsi

Fitur ini memungkinkan admin kecamatan untuk membuat, mengelola, dan mempublikasikan informasi atau pengumuman yang dapat dilihat oleh admin desa. Admin desa dapat melihat informasi tersebut beserta statistik desa mereka dalam bentuk chart.

## Fitur untuk Admin Kecamatan

### 1. Manajemen Informasi (CRUD)

-   **Create**: Membuat informasi baru dengan judul, konten, kategori, dan status
-   **Read**: Melihat daftar informasi dengan filter status dan kategori
-   **Update**: Mengedit informasi yang sudah ada
-   **Delete**: Menghapus informasi

### 2. Kategori Informasi

-   Pengumuman
-   Informasi
-   Berita

### 3. Status Informasi

-   **Draft**: Informasi disimpan namun belum dipublikasikan
-   **Published**: Informasi dipublikasikan dan dapat dilihat admin desa

### 4. Fitur Tambahan

-   Toggle status (draft ↔ published)
-   Filter berdasarkan status dan kategori
-   Preview konten saat membuat/edit
-   Informasi detail pembuat dan tanggal

## Fitur untuk Admin Desa

### 1. View Informasi

-   Melihat informasi yang sudah dipublikasikan dari kecamatan
-   Tampilan card dengan preview konten
-   Pagination untuk informasi yang banyak

### 2. Statistik Desa

-   **Statistik Penduduk**: Total, gender, status KTP
-   **Statistik Perangkat**: Jumlah perangkat desa
-   **Statistik Aset**: Aset desa dan aset warga dengan nilai
-   **Klasifikasi Usia**: Distribusi usia penduduk
-   **Top Pekerjaan**: 5 pekerjaan terbanyak

### 3. Chart Visualisasi

-   Chart gender (doughnut)
-   Chart status KTP (doughnut)
-   Chart klasifikasi usia (bar)
-   Data statistik dalam bentuk card

## Struktur Database

### Tabel `information`

```sql
- id (primary key)
- judul (string)
- konten (text)
- kategori (string: pengumuman, informasi, berita)
- status (enum: draft, published)
- created_by (foreign key ke users)
- published_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

## Routes

### Admin Kecamatan

-   `GET /admin/information` - Daftar informasi
-   `GET /admin/information/create` - Form tambah informasi
-   `POST /admin/information` - Simpan informasi baru
-   `GET /admin/information/{id}` - Detail informasi
-   `GET /admin/information/{id}/edit` - Form edit informasi
-   `PUT /admin/information/{id}` - Update informasi
-   `DELETE /admin/information/{id}` - Hapus informasi
-   `POST /admin/information/{id}/toggle-status` - Toggle status

### Admin Desa

-   `GET /admin-desa/information` - Informasi & statistik
-   `GET /admin-desa/information/{id}` - Detail informasi

## Menu Navigation

### Admin Kecamatan

Dropdown "Informasi" dengan submenu:

-   Daftar Informasi
-   Tambah Informasi

### Admin Desa

Dropdown "Informasi" dengan submenu:

-   Informasi & Statistik

## File yang Dibuat/Dimodifikasi

### Models

-   `app/Models/Information.php`

### Controllers

-   `app/Http/Controllers/Admin/InformationController.php`
-   `app/Http/Controllers/AdminDesa/InformationController.php`

### Views

-   `resources/views/admin/information/index.blade.php`
-   `resources/views/admin/information/create.blade.php`
-   `resources/views/admin/information/edit.blade.php`
-   `resources/views/admin/information/show.blade.php`
-   `resources/views/admin-desa/information/index.blade.php`
-   `resources/views/admin-desa/information/show.blade.php`

### Migrations

-   `database/migrations/xxxx_create_information_table.php`

### Seeders

-   `database/seeders/InformationSeeder.php`

### Routes

-   `routes/web.php` (dimodifikasi)

### Layouts

-   `resources/views/layouts/admin.blade.php` (dimodifikasi)
-   `resources/views/layouts/admin-desa.blade.php` (dimodifikasi)

## Cara Menggunakan

### Untuk Admin Kecamatan

1. Login sebagai admin kecamatan
2. Klik dropdown "Informasi" di sidebar
3. Pilih "Daftar Informasi" untuk melihat semua informasi
4. Klik "Tambah Informasi" untuk membuat informasi baru
5. Isi form dengan judul, konten, kategori, dan status
6. Klik "Simpan Informasi"
7. Untuk mempublikasikan, ubah status dari "Draft" ke "Publikasikan"

### Untuk Admin Desa

1. Login sebagai admin desa
2. Klik dropdown "Informasi" di sidebar
3. Pilih "Informasi & Statistik"
4. Lihat informasi dari kecamatan dan statistik desa
5. Klik "Baca" untuk melihat detail informasi

## Data Sample

Seeder telah dibuat dengan 5 data sample informasi untuk testing:

-   4 informasi dengan status published
-   1 informasi dengan status draft
-   Berbagai kategori (pengumuman, informasi, berita)

## Dependencies

-   Chart.js untuk visualisasi data
-   Bootstrap untuk styling
-   Font Awesome untuk icons
