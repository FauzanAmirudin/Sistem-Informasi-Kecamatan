@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Mobile Navbar Toggle -->
        <div class="d-md-none bg-gradient-primary text-white p-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fs-4 me-2"></i>
                <span>Admin Kecamatan</span>
            </div>
            <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar" aria-expanded="false">
                <i class="fas fa-bars fs-4"></i>
            </button>
        </div>
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-gradient-primary sidebar collapse">
            <div class="position-sticky pt-3">
                <!-- User Info -->
                <div class="text-center mb-4 px-3">
                    <div class="bg-white bg-opacity-10 rounded-3 p-3 mb-3">
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" 
                             class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover;">
                        <h6 class="text-white fw-bold mb-1">Admin Kecamatan</h6>
                        <small class="text-white-50">{{ Auth::user()->name }}</small>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <ul class="nav flex-column px-2">
                    <!-- Dashboard Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.monitoring') || request()->routeIs('admin.statistik') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#dashboardCollapse" role="button" aria-expanded="false" aria-controls="dashboardCollapse">
                            <div>
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.monitoring') || request()->routeIs('admin.statistik') ? 'show' : '' }}" id="dashboardCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        Data Statistik
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.monitoring') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.monitoring') }}">
                                        <i class="fas fa-map-marked-alt me-2"></i>
                                        Monitoring Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.statistik') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.statistik') }}">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Statistik Detail
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Master Data Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin.desa.*') || request()->routeIs('admin.perangkat-desa.*') || request()->routeIs('admin.penduduk.*') || request()->routeIs('admin.aset-desa.*') || request()->routeIs('admin.aset-tanah-warga.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#masterDataCollapse" role="button" aria-expanded="false" aria-controls="masterDataCollapse">
                            <div>
                                <i class="fas fa-database me-2"></i>
                                Master Data
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.desa.*') || request()->routeIs('admin.perangkat-desa.*') || request()->routeIs('admin.penduduk.*') || request()->routeIs('admin.aset-desa.*') || request()->routeIs('admin.aset-tanah-warga.*') ? 'show' : '' }}" id="masterDataCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.desa.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.desa.index') }}">
                                        <i class="fas fa-home me-2"></i>
                                        Data Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.perangkat-desa.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.perangkat-desa.index') }}">
                                        <i class="fas fa-users me-2"></i>
                                        Perangkat Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.penduduk.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.penduduk.index') }}">
                                        <i class="fas fa-user-friends me-2"></i>
                                        Data Penduduk
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.aset-desa.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.aset-desa.index') }}">
                                        <i class="fas fa-building me-2"></i>
                                        Aset Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.aset-tanah-warga.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.aset-tanah-warga.index') }}">
                                        <i class="fas fa-map me-2"></i>
                                        Aset Tanah Warga
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Informasi Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin.information.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#informasiCollapse" role="button" aria-expanded="false" aria-controls="informasiCollapse">
                            <div>
                                <i class="fas fa-newspaper me-2"></i>
                                Informasi
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.information.*') ? 'show' : '' }}" id="informasiCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.information.index') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.information.index') }}">
                                        <i class="fas fa-list me-2"></i>
                                        Daftar Informasi
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.information.create') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.information.create') }}">
                                        <i class="fas fa-plus me-2"></i>
                                        Tambah Informasi
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Konfigurasi Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin.dokumen.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.profile.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#konfigurasiCollapse" role="button" aria-expanded="false" aria-controls="konfigurasiCollapse">
                            <div>
                                <i class="fas fa-cog me-2"></i>
                                Konfigurasi
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.dokumen.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.profile.*') ? 'show' : '' }}" id="konfigurasiCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.profile.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i>
                                        Edit Profil
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.dokumen.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.dokumen.index') }}">
                                        <i class="fas fa-folder me-2"></i>
                                        Dokumen & Bantuan
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin.users.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin.users.index') }}">
                                        <i class="fas fa-user-cog me-2"></i>
                                        Manajemen User
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item mt-4">
                        <hr class="text-white-50">
                        <a class="nav-link text-white sidebar-link rounded-2 text-danger" 
                           href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-3 px-md-4" style="margin-left: 16.666667%;">
            <!-- Header with Logo and Welcome Message -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}" alt="Logo Kabupaten OKU Timur" class="me-3" style="height: 50px;">
                    <h1 class="h2 text-dark fw-bold mb-0">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-end">
                        <p class="mb-0 fw-bold">Selamat Datang, Admin Kecamatan</p>
                        <small class="text-muted">{{ Auth::user()->name }}</small>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('page-actions')
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2 list-unstyled">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-dot-circle me-2 small"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Loading Indicator -->
            <div class="loading d-none text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </div>

            <!-- Page Content -->
            <div class="fade-in">
                @yield('admin-content')
            </div>
        </main>
    </div>
</div>
@endsection