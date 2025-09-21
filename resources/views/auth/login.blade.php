<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login - Sistem Informasi Kecamatan Belitang Jaya</title>
    <link rel="icon" type="image/png" href="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom CSS dengan warna dari app.blade.php dan animasi */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0504ff 0%, #3d5af1 100%) !important;
        }
        .shadow-custom {
            box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
        }
        .btn-gradient {
            background: linear-gradient(45deg, #0504ff, #3d5af1);
            border: none;
        }
        .btn-gradient:hover {
            background: linear-gradient(45deg, #0000d6, #2d4ae1);
        }
        
        /* Animasi untuk left side panel */
        .info-panel {
            position: relative;
            overflow: hidden;
            animation: slideInFromLeft 0.8s ease-out;
        }
        
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
        
        /* Animasi untuk logo */
        .logo-container {
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* Animasi untuk icon info */
        .info-icons .col-4 {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .info-icons .col-4:nth-child(1) {
            animation-delay: 0.2s;
        }
        
        .info-icons .col-4:nth-child(2) {
            animation-delay: 0.4s;
        }
        
        .info-icons .col-4:nth-child(3) {
            animation-delay: 0.6s;
        }
        
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
        
        /* Animasi untuk text */
        .info-panel h2, .info-panel h3 {
            animation: slideInFromBottom 0.8s ease-out;
            animation-fill-mode: both;
        }
        
        .info-panel h2 {
            animation-delay: 0.3s;
        }
        
        .info-panel h3 {
            animation-delay: 0.5s;
        }
        
        .info-panel .lead {
            animation: slideInFromBottom 0.8s ease-out;
            animation-delay: 0.7s;
            animation-fill-mode: both;
        }
        
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
        
        /* Hover effect untuk icon */
        .info-icons .col-4:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        
        .info-icons .col-4:hover i {
            color: #ffd700 !important;
            transition: color 0.3s ease;
        }
        
        /* Animasi untuk right side (form login) */
        .login-form {
            animation: slideInFromRight 0.8s ease-out;
            animation-delay: 0.3s;
            animation-fill-mode: both;
        }
        
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
        
        /* Animasi untuk form elements */
        .form-control, .btn, .form-check {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .form-control:nth-child(1) {
            animation-delay: 0.5s;
        }
        
        .form-control:nth-child(2) {
            animation-delay: 0.7s;
        }
        
        .form-check {
            animation-delay: 0.9s;
        }
        
        .btn {
            animation-delay: 1.1s;
        }
        
        /* Responsive styles */
        @media (max-width: 991.98px) {
            .info-panel-text {
                padding: 2rem !important;
            }
            .login-form {
                padding: 2rem !important;
            }
            .row.g-0 {
                flex-direction: column-reverse;
            }
            .order-1 {
                order: 1 !important;
            }
            .order-2 {
                order: 2 !important;
            }
            
            /* Disable animasi di mobile untuk performa */
            .info-panel, .login-form, .info-icons .col-4, 
            .info-panel h2, .info-panel h3, .info-panel .lead,
            .form-control, .btn, .form-check {
                animation: none !important;
            }
            
            /* Logo tetap ada animasi tapi lebih sederhana */
            .logo-container {
                animation: logoFloat 4s ease-in-out infinite;
            }
        }
        
        @media (max-width: 767.98px) {
            .card-body {
                padding: 0 !important;
            }
            .info-icons .col-4 {
                padding: 0.5rem;
            }
            .info-icons .fs-1 {
                font-size: 1.5rem !important;
            }
            .input-group-text {
                padding: 0.5rem 0.75rem;
            }
            .form-control {
                padding: 0.5rem 0.75rem;
                font-size: 1rem;
            }
            .form-label {
                margin-bottom: 0.25rem;
            }
            
            /* Logo responsive untuk mobile */
            .logo-container img {
                max-height: 80px !important;
                padding: 10px !important;
            }
        }
        
        @media (max-width: 575.98px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .info-panel-text {
                padding: 1.5rem !important;
            }
            .login-form {
                padding: 1.5rem !important;
            }
            h2 {
                font-size: 1.5rem;
            }
            h3 {
                font-size: 1.25rem;
            }
            h4 {
                font-size: 1.2rem;
            }
            .lead {
                font-size: 1rem;
            }
            .display-1 {
                font-size: 3rem !important;
            }
            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
            .alert {
                padding: 0.75rem;
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }
            .card {
                border-radius: 0.75rem !important;
            }
            .form-check-label {
                font-size: 0.9rem;
            }
            
            /* Logo untuk layar sangat kecil */
            .logo-container img {
                max-height: 60px !important;
                padding: 8px !important;
            }
        }
        
        /* Extra small devices */
        @media (max-width: 359.98px) {
            .info-panel-text {
                padding: 1rem !important;
            }
            .login-form {
                padding: 1rem !important;
            }
            .display-1 {
                font-size: 2.5rem !important;
            }
            .info-icons .col-4 {
                padding: 0.25rem;
            }
            .info-icons .fs-1 {
                font-size: 1.25rem !important;
            }
            .info-icons small {
                font-size: 0.7rem;
            }
            
            /* Logo untuk extra small devices */
            .logo-container img {
                max-height: 50px !important;
                padding: 5px !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-primary min-vh-100 d-flex align-items-center py-3 py-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card border-0 shadow-custom rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- Left Side - Info Panel -->
                            <div class="col-lg-6 bg-gradient-primary text-white d-flex align-items-center order-2 order-lg-1 info-panel">
                                <div class="p-5 text-center w-100 info-panel-text">
                                    <div class="mb-4">
                                        <!-- Logo Kabupaten OKU Timur -->
                                        <div class="logo-container mb-4">
                                            <img src="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}" 
                                                 alt="Logo Kabupaten OKU Timur" 
                                                 class="img-fluid" 
                                                 style="max-height: 120px; width: auto; 
                                                        border-radius: 25%; 
                                                        box-shadow: 0 8px 32px rgba(255, 255, 255, 0.2);
                                                        background: rgba(255, 255, 255, 0.9);
                                                        padding: 15px;">
                                        </div>
                                        <h2 class="fw-bold mb-2">Sistem Informasi</h2>
                                        <h3 class="fw-bold">Kecamatan Belitang Jaya</h3>
                                        <p class="mb-0 text-white-50">Kabupaten Ogan Komering Ulu Timur</p>
                                    </div>
                                    <p class="lead mb-4">
                                        Sistem terpadu untuk mengelola data desa, penduduk, perangkat desa, dan aset di wilayah Kecamatan Belitang Jaya
                                    </p>
                                    <div class="row text-center info-icons">
                                        <div class="col-4">
                                            <i class="fas fa-home fs-1 mb-2 d-block"></i>
                                            <small>Data Desa</small>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-users fs-1 mb-2 d-block"></i>
                                            <small>Data Penduduk</small>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-chart-bar fs-1 mb-2 d-block"></i>
                                            <small>Statistik</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Side - Login Form -->
                            <div class="col-lg-6 order-1 order-lg-2">
                                <div class="p-5 login-form">
                                    <div class="text-center mb-4">
                                        <h4 class="fw-bold text-dark">Masuk ke Sistem</h4>
                                        <p class="text-muted">Silakan masukkan email dan password Anda</p>
                                    </div>

                                    <!-- Flash Messages -->
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        
                                        <!-- Email Field -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-semibold">Email</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-envelope text-muted"></i>
                                                </span>
                                                <input type="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" 
                                                       name="email" 
                                                       value="{{ old('email') }}" 
                                                       placeholder="Masukkan email Anda"
                                                       required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Password Field -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label fw-semibold">Password</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-lock text-muted"></i>
                                                </span>
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password" 
                                                       placeholder="Masukkan password Anda"
                                                       required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="mb-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                                <label class="form-check-label" for="remember">
                                                    Ingat saya
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-gradient btn-lg py-3">
                                                <i class="fas fa-sign-in-alt me-2"></i>
                                                Masuk
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Demo Accounts Info telah dihapus -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk meningkatkan pengalaman pengguna pada perangkat mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Meningkatkan area sentuh untuk elemen interaktif pada perangkat mobile
            if (window.innerWidth < 768) {
                // Menambahkan padding pada elemen form untuk meningkatkan area sentuh
                document.querySelectorAll('.form-control, .btn, .input-group-text').forEach(function(el) {
                    el.style.touchAction = 'manipulation';
                });
                
                // Fokus otomatis pada field email saat halaman dimuat
                setTimeout(function() {
                    const emailField = document.getElementById('email');
                    if (emailField) {
                        emailField.focus();
                    }
                }, 500);
            }
            
            // Menambahkan validasi form sederhana
            const loginForm = document.querySelector('form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const emailField = document.getElementById('email');
                    const passwordField = document.getElementById('password');
                    let isValid = true;
                    
                    // Validasi email
                    if (!emailField.value.trim() || !emailField.value.includes('@')) {
                        emailField.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        emailField.classList.remove('is-invalid');
                    }
                    
                    // Validasi password
                    if (!passwordField.value.trim() || passwordField.value.length < 6) {
                        passwordField.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        passwordField.classList.remove('is-invalid');
                    }
                    
                    // Jika tidak valid, mencegah pengiriman form
                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>