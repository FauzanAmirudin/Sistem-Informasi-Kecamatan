# Update Halaman Login dengan Animasi Left Side Panel

## Deskripsi

Halaman login telah diperbarui dengan animasi yang smooth pada left side panel, menampilkan logo Kabupaten OKU Timur, dan menggunakan warna yang konsisten dengan app.blade.php.

## Fitur yang Ditambahkan

### 1. Animasi Left Side Panel

-   **Animasi Utama**: Left side panel muncul dari belakang right side dengan efek slide dari kiri
-   **Durasi**: 0.8 detik dengan easing `ease-out`
-   **Efek**: `slideInFromLeft` - panel bergerak dari `translateX(-100%)` ke `translateX(0)`

### 2. Logo Kabupaten OKU Timur

-   **Lokasi**: Di bagian atas left side panel
-   **Animasi**: Logo mengambang dengan efek `logoFloat` (3 detik, infinite)
-   **Styling**:
    -   Bentuk lingkaran dengan border-radius 50%
    -   Background semi-transparan putih
    -   Box shadow untuk efek depth
    -   Filter invert untuk membuat logo putih di background biru
    -   Padding untuk spacing yang baik

### 3. Animasi Elemen Individual

-   **Text Elements**:
    -   Judul "Sistem Informasi" muncul dengan delay 0.3s
    -   Subtitle "Kecamatan Belitang Jaya" muncul dengan delay 0.5s
    -   Deskripsi muncul dengan delay 0.7s
-   **Icon Info**:
    -   Data Desa (delay 0.2s)
    -   Data Penduduk (delay 0.4s)
    -   Statistik (delay 0.6s)
-   **Form Elements**:
    -   Email field (delay 0.5s)
    -   Password field (delay 0.7s)
    -   Remember me checkbox (delay 0.9s)
    -   Submit button (delay 1.1s)

### 4. Right Side Animation

-   **Animasi**: Form login muncul dari kanan dengan delay 0.3s
-   **Efek**: `slideInFromRight` - bergerak dari `translateX(100%)` ke `translateX(0)`

## Warna yang Digunakan

### Gradient Background

```css
background: linear-gradient(135deg, #0504ff 0%, #3d5af1 100%);
```

-   **Primary Blue**: #0504ff
-   **Secondary Blue**: #3d5af1
-   Konsisten dengan warna di app.blade.php

### Button Gradient

```css
background: linear-gradient(45deg, #0504ff, #3d5af1);
```

-   Hover state: `#0000d6` dan `#2d4ae1`

## Animasi yang Diimplementasikan

### 1. slideInFromLeft

```css
@keyframes slideInFromLeft {
    0% {
        transform: translateX(-100%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}
```

### 2. slideInFromRight

```css
@keyframes slideInFromRight {
    0% {
        transform: translateX(100%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}
```

### 3. logoFloat

```css
@keyframes logoFloat {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}
```

### 4. fadeInUp

```css
@keyframes fadeInUp {
    0% {
        transform: translateY(30px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
```

### 5. slideInFromBottom

```css
@keyframes slideInFromBottom {
    0% {
        transform: translateY(30px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
```

## Responsive Design

### Desktop (> 991px)

-   Semua animasi aktif
-   Logo ukuran penuh (120px)
-   Animasi kompleks dengan multiple delays

### Tablet (768px - 991px)

-   Animasi di-disable untuk performa
-   Logo tetap ada animasi float sederhana
-   Layout tetap responsif

### Mobile (< 768px)

-   Animasi di-disable untuk performa
-   Logo ukuran 80px
-   Layout stack vertikal

### Small Mobile (< 576px)

-   Logo ukuran 60px
-   Padding dan spacing disesuaikan

### Extra Small (< 360px)

-   Logo ukuran 50px
-   Layout minimal untuk layar kecil

## Hover Effects

### Icon Info Hover

```css
.info-icons .col-4:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}

.info-icons .col-4:hover i {
    color: #ffd700 !important;
    transition: color 0.3s ease;
}
```

## Struktur HTML yang Diperbarui

### Left Side Panel

```html
<div
    class="col-lg-6 bg-gradient-primary text-white d-flex align-items-center order-2 order-lg-1 info-panel"
>
    <div class="p-5 text-center w-100 info-panel-text">
        <div class="mb-4">
            <!-- Logo Kabupaten OKU Timur -->
            <div class="logo-container mb-4">
                <img
                    src="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}"
                    alt="Logo Kabupaten OKU Timur"
                    class="img-fluid"
                    style="max-height: 120px; width: auto; filter: brightness(0) invert(1); 
                            border-radius: 50%; 
                            box-shadow: 0 8px 32px rgba(255, 255, 255, 0.1);
                            background: rgba(255, 255, 255, 0.1);
                            padding: 15px;"
                />
            </div>
            <h2 class="fw-bold mb-2">Sistem Informasi</h2>
            <h3 class="fw-bold">Kecamatan Belitang Jaya</h3>
            <p class="mb-0 text-white-50">Kabupaten Ogan Komering Ulu Timur</p>
        </div>
        <!-- ... rest of content ... -->
    </div>
</div>
```

## Performa Optimasi

### Mobile Performance

-   Animasi di-disable di mobile untuk performa yang lebih baik
-   Logo tetap ada animasi float yang ringan
-   Layout tetap responsif dan user-friendly

### Animation Performance

-   Menggunakan `transform` dan `opacity` untuk animasi yang smooth
-   `animation-fill-mode: both` untuk mencegah flicker
-   Durasi animasi yang tidak terlalu lama (0.6s - 0.8s)

## File yang Dimodifikasi

-   `resources/views/auth/login.blade.php`

## Hasil Akhir

-   ✅ Left side panel muncul dari belakang right side dengan animasi smooth
-   ✅ Logo Kabupaten OKU Timur ditampilkan dengan animasi float
-   ✅ Warna konsisten dengan app.blade.php
-   ✅ Animasi tidak terlalu lama dan smooth
-   ✅ Responsive design untuk semua ukuran layar
-   ✅ Performa optimal di mobile devices
-   ✅ Hover effects yang menarik
-   ✅ Keterangan yang ada tetap dipertahankan

## Catatan

-   Animasi menggunakan CSS3 keyframes untuk performa yang optimal
-   Logo menggunakan filter invert untuk membuatnya putih di background biru
-   Semua animasi memiliki fallback untuk browser yang tidak mendukung
-   Mobile-first approach dengan progressive enhancement untuk desktop

