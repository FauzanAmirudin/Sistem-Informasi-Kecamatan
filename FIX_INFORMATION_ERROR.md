# Perbaikan Error Halaman Informasi Admin Desa

## Masalah yang Ditemukan

Error `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'no_ktp' in 'where clause'` terjadi karena:

1. **Kolom KTP Salah**: Query menggunakan `no_ktp` padahal kolom yang benar adalah `memiliki_ktp` (boolean)
2. **Kolom Aset Salah**: Query menggunakan `nilai_aset` padahal kolom yang benar adalah `nilai_sekarang` atau `nilai_perolehan`
3. **Query Klasifikasi Usia**: Menggunakan query SQL mentah yang mungkin tidak kompatibel dengan struktur tabel

## Perbaikan yang Dilakukan

### 1. Perbaikan Query KTP

**Sebelum:**

```php
$pendudukBerKTP = Penduduk::where('desa_id', $desaId)->where('no_ktp', '!=', null)->count();
```

**Sesudah:**

```php
$pendudukBerKTP = Penduduk::where('desa_id', $desaId)->where('memiliki_ktp', true)->count();
```

### 2. Perbaikan Query Aset Desa

**Sebelum:**

```php
$totalNilaiAsetDesa = AsetDesa::where('desa_id', $desaId)->sum('nilai_aset');
```

**Sesudah:**

```php
$totalNilaiAsetDesa = AsetDesa::where('desa_id', $desaId)->where('is_current', true)
    ->get()
    ->sum(function($aset) {
        return $aset->nilai_sekarang ?? $aset->nilai_perolehan ?? 0;
    });
```

### 3. Perbaikan Query Aset Tanah Warga

**Sebelum:**

```php
$totalNilaiAsetWarga = AsetTanahWarga::where('desa_id', $desaId)->sum('nilai_tanah');
```

**Sesudah:**

```php
$totalNilaiAsetWarga = AsetTanahWarga::where('desa_id', $desaId)
    ->get()
    ->sum(function($aset) {
        return $aset->nilai_tanah;
    });
```

### 4. Perbaikan Query Klasifikasi Usia

**Sebelum:**

```php
$klasifikasiUsia = Penduduk::where('desa_id', $desaId)
    ->selectRaw('
        CASE
            WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 17 THEN "Anak-anak (< 17 tahun)"
            WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 17 AND 30 THEN "Remaja (17-30 tahun)"
            WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 31 AND 50 THEN "Dewasa (31-50 tahun)"
            WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 51 AND 65 THEN "Lansia (51-65 tahun)"
            ELSE "Manula (> 65 tahun)"
        END as klasifikasi_usia,
        COUNT(*) as jumlah
    ')
    ->groupBy('klasifikasi_usia')
    ->orderBy('jumlah', 'desc')
    ->get();
```

**Sesudah:**

```php
$klasifikasiUsia = Penduduk::where('desa_id', $desaId)
    ->whereNotNull('klasifikasi_usia')
    ->selectRaw('klasifikasi_usia, COUNT(*) as jumlah')
    ->groupBy('klasifikasi_usia')
    ->orderBy('jumlah', 'desc')
    ->get();
```

### 5. Perbaikan Query Top Pekerjaan

**Sebelum:**

```php
$topPekerjaan = Penduduk::where('desa_id', $desaId)
    ->where('pekerjaan', '!=', null)
    ->where('pekerjaan', '!=', '')
    ->selectRaw('pekerjaan, COUNT(*) as jumlah')
    ->groupBy('pekerjaan')
    ->orderBy('jumlah', 'desc')
    ->limit(10)
    ->get();
```

**Sesudah:**

```php
$topPekerjaan = Penduduk::where('desa_id', $desaId)
    ->whereNotNull('pekerjaan')
    ->where('pekerjaan', '!=', '')
    ->selectRaw('pekerjaan, COUNT(*) as jumlah')
    ->groupBy('pekerjaan')
    ->orderBy('jumlah', 'desc')
    ->limit(10)
    ->get();
```

### 6. Penambahan Error Handling

-   Menambahkan try-catch untuk menangani error database
-   Inisialisasi variabel dengan nilai default
-   Logging error untuk debugging
-   Flash message untuk memberitahu user jika ada masalah

### 7. Penambahan Data Sample

Membuat `SampleDataSeeder` dengan data:

-   5 penduduk sample dengan berbagai karakteristik
-   2 perangkat desa sample
-   2 aset desa sample
-   2 aset tanah warga sample
-   1 desa sample
-   1 user admin desa sample

## File yang Dimodifikasi

-   `app/Http/Controllers/AdminDesa/InformationController.php`
-   `database/seeders/SampleDataSeeder.php` (baru)

## Cara Testing

1. Jalankan seeder: `php artisan db:seed --class=SampleDataSeeder`
2. Login sebagai admin desa dengan email: `admin@desasample.com` dan password: `password`
3. Akses halaman informasi: `/admin-desa/information`

## Data Sample yang Dibuat

-   **Desa**: Desa Sample
-   **Admin Desa**: admin@desasample.com (password: password)
-   **Penduduk**: 5 orang dengan berbagai karakteristik
-   **Perangkat**: 2 perangkat desa
-   **Aset Desa**: 2 aset (kantor desa, mobil operasional)
-   **Aset Warga**: 2 tanah warga

## Status

✅ Error telah diperbaiki dan halaman informasi admin desa sudah dapat diakses dengan normal.
