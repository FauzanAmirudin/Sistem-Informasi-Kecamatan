@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Mobile Navbar Toggle -->
        <div class="d-md-none bg-gradient-primary text-white p-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fs-4 me-2"></i>
                <span>Admin Desa</span>
                @if(Auth::user()->desa)
                    <span class="ms-2 badge bg-light text-primary">{{ Auth::user()->desa->nama_desa }}</span>
                @endif
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
                        <h6 class="text-white fw-bold mb-1">Admin Desa</h6>
                        <small class="text-white-50">{{ Auth::user()->name }}</small>
                        @if(Auth::user()->desa)
                            <div class="mt-1 badge bg-light text-primary">{{ Auth::user()->desa->nama_desa }}</div>
                        @endif
                        {{-- <div class="mt-2">
                            <a href="{{ route('admin-desa.profile.edit') }}" class="btn btn-sm btn-light text-primary">
                                <i class="fas fa-user-edit me-1"></i> Edit Profil
                            </a>
                        </div> --}}
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <ul class="nav flex-column px-2">
                    <!-- Dashboard Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin-desa.dashboard') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#dashboardCollapse" role="button" aria-expanded="false" aria-controls="dashboardCollapse">
                            <div>
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin-desa.dashboard') ? 'show' : '' }}" id="dashboardCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.dashboard') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.dashboard') }}">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        Dashboard
                                     </a>
                                 </li>
                             </ul>
                        </div>
                    </li>
                    
                    <!-- Master Data Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin-desa.desa.*') || request()->routeIs('admin-desa.profil.*') || request()->routeIs('admin-desa.penduduk.*') || request()->routeIs('admin-desa.perangkat-desa.*') || request()->routeIs('admin-desa.aset-desa.*') || request()->routeIs('admin-desa.aset-tanah-warga.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#masterDataCollapse" role="button" aria-expanded="false" aria-controls="masterDataCollapse">
                            <div>
                                <i class="fas fa-database me-2"></i>
                                Master Data
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin-desa.desa.*') || request()->routeIs('admin-desa.profil.*') || request()->routeIs('admin-desa.penduduk.*') || request()->routeIs('admin-desa.perangkat-desa.*') || request()->routeIs('admin-desa.aset-desa.*') || request()->routeIs('admin-desa.aset-tanah-warga.*') ? 'show' : '' }}" id="masterDataCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.desa.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.desa.index') }}">
                                        <i class="fas fa-home me-2"></i>
                                        Data Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.profil.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.profil.index') }}">
                                        <i class="fas fa-id-card me-2"></i>
                                        Profil Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.penduduk.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.penduduk.index') }}">
                                        <i class="fas fa-users me-2"></i>
                                        Data Penduduk
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.perangkat-desa.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.perangkat-desa.index') }}">
                                        <i class="fas fa-user-tie me-2"></i>
                                        Perangkat Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.aset-desa.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.aset-desa.index') }}">
                                        <i class="fas fa-building me-2"></i>
                                        Aset Desa
                                    </a>
                                </li>
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.aset-tanah-warga.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.aset-tanah-warga.index') }}">
                                        <i class="fas fa-home me-2"></i>
                                        Aset Tanah Warga
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Informasi Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin-desa.information.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#informasiCollapse" role="button" aria-expanded="false" aria-controls="informasiCollapse">
                            <div>
                                <i class="fas fa-newspaper me-2"></i>
                                Informasi
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin-desa.information.*') ? 'show' : '' }}" id="informasiCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.information.index') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.information.index') }}">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Informasi & Statistik
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Konfigurasi Dropdown -->
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 d-flex justify-content-between align-items-center {{ request()->routeIs('admin-desa.dokumen.*') || request()->routeIs('admin-desa.profile.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           data-bs-toggle="collapse" href="#konfigurasiCollapse" role="button" aria-expanded="false" aria-controls="konfigurasiCollapse">
                            <div>
                                <i class="fas fa-cog me-2"></i>
                                Konfigurasi
                            </div>
                            <i class="fas fa-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin-desa.dokumen.*') || request()->routeIs('admin-desa.profile.*') ? 'show' : '' }}" id="konfigurasiCollapse">
                            <ul class="nav flex-column ms-3 mt-1">
                                {{-- <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.profile.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i>
                                        Edit Profil
                                    </a>
                                </li> --}}
                                <li class="nav-item mb-1">
                                    <a class="nav-link sidebar-link rounded-2 py-1 {{ request()->routeIs('admin-desa.dokumen.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                                       href="{{ route('admin-desa.dokumen.index') }}">
                                        <i class="fas fa-folder me-2"></i>
                                        Dokumen
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 {{ request()->routeIs('admin-desa.faq.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           href="{{ route('admin-desa.faq.index') }}">
                            <i class="fas fa-question-circle me-2"></i>
                            FAQ & Panduan
                        </a>
                    </li>
                    
                    <li class="nav-item mb-1">
                        <a class="nav-link sidebar-link rounded-2 {{ request()->routeIs('admin-desa.profile.*') ? 'bg-white text-primary fw-bold' : 'text-white' }}" 
                           href="{{ route('admin-desa.profile.edit') }}">
                            <i class="fas fa-user-cog me-2"></i>
                            Profil Saya
                        </a>
                    </li>
                    
                    <li class="nav-item mt-4">
                        <hr class="text-white-50">
                        <form action="{{ route('logout') }}" method="POST" class="d-grid px-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 ms-sm-auto px-3 px-md-4 py-4 main-content-mobile" style="margin-left: 16.666667%;">
            <!-- Layout Header with Logo and Welcome Message -->
            <div class="border-bottom mb-3">
                <!-- Mobile Header -->
                <div class="d-md-none pt-3 pb-2">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}" alt="Logo" class="me-2" style="height: 35px;">
                            <div>
                                <h1 class="h5 mb-0">Data Penduduk Desa</h1>
                                <small class="text-muted d-block">Sistem Informasi Kecamatan</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="mb-1 fw-bold small">Selamat Datang, Admin Desa</p>
                        <small class="text-muted">{{ Auth::user()->name }}</small>
                        @if(Auth::user()->desa)
                            <br><small class="text-primary">{{ Auth::user()->desa->nama_desa }}</small>
                        @endif
                    </div>
                </div>
                
                <!-- Desktop Header -->
                <div class="d-none d-md-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}" alt="Logo Kabupaten OKU Timur" class="me-3" style="height: 50px;">
                        <div>
                            <h1 class="h2 mb-0">Data Penduduk Desa</h1>
                            <small class="text-muted">Sistem Informasi Kecamatan Belitang Jaya</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-end">
                            <p class="mb-0 fw-bold">Selamat Datang, Admin Desa</p>
                            <small class="text-muted">{{ Auth::user()->name }} @if(Auth::user()->desa) - {{ Auth::user()->desa->nama_desa }} @endif</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Header with Title and Actions -->
            <div class="pt-2 pb-2 mb-3">
                <!-- Mobile Page Header -->
                <div class="d-md-none">
                    <div class="mb-2">
                        <h2 class="h5 mb-1 text-dark">@yield('page-title', 'Dashboard')</h2>
                        @hasSection('page-subtitle')
                            <p class="text-muted mb-2 small">@yield('page-subtitle')</p>
                        @endif
                    </div>
                    @hasSection('page-actions')
                        <div class="d-grid gap-2">
                            @yield('page-actions')
                        </div>
                    @endif
                </div>
                
                <!-- Desktop Page Header -->
                <div class="d-none d-md-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <div>
                        <h2 class="h4 mb-0 text-dark">@yield('page-title', 'Dashboard')</h2>
                        @hasSection('page-subtitle')
                            <p class="text-muted mb-0">@yield('page-subtitle')</p>
                        @endif
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('page-actions')
                    </div>
                </div>
            </div>
            
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Loading Indicator -->
            <div id="loading-indicator" class="text-center d-none">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('[data-bs-target=".sidebar"]');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Close sidebar when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('show');
            }
        });
    }
    
    // Mobile page actions responsive handling
    const pageActions = document.querySelector('.btn-toolbar');
    if (pageActions && window.innerWidth < 768) {
        pageActions.classList.add('mobile-page-actions');
    }
});
</script>
@endpush