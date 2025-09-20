<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistem Informasi Kecamatan Belitang Jaya')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Lambang_Kabupaten_OKU_Timur.png') }}" type="image/png">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Leaflet CSS untuk peta -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Custom CSS Pagination -->
    <link rel="stylesheet" href="{{ asset('css/custom-pagination.css') }}">
    
    <!-- Custom CSS menggunakan Bootstrap utilities -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0504ff 0%, #3d5af1 100%) !important;
        }
        
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #4e73df 0%, #8cb3ff 100%) !important;
        }
        
        .shadow-custom {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Tambahan perpaduan warna biru */
        .bg-blue-light {
            background-color: #e6e9ff !important;
        }
        
        .bg-blue-medium {
            background-color: #b1b8ff !important;
        }
        
        .text-blue-primary {
            color: #0504ff !important;
        }
        
        .border-blue-primary {
            border-color: #0504ff !important;
        }
        
        .btn-blue-primary {
            background-color: #0504ff;
            border-color: #0504ff;
            color: white;
        }
        
        .btn-blue-primary:hover {
            background-color: #0000d6;
            border-color: #0000d6;
            color: white;
        }
        
        /* Sidebar styling */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar .position-sticky {
            position: sticky;
            top: 0;
            height: 100vh;
            padding-top: 1.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar-link {
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .sidebar-link:hover {
            transform: translateX(5px);
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 56px; /* Height of mobile navbar */
                width: 100%;
                z-index: 999;
            }
            
            .sidebar.collapse:not(.show) {
                display: none;
            }
            
            .sidebar.collapse.show {
                display: block;
            }
            
            main {
                margin-left: 0 !important;
            }
            
            .sidebar-link {
                padding: 0.75rem 1rem;
                margin-bottom: 0.5rem;
            }
            
            .sidebar-link i {
                font-size: 1.1rem;
                min-width: 1.5rem;
            }
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        
        /* Responsive card styles */
        .card {
            transition: box-shadow 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 767.98px) {
            .card-header {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                align-items: center;
            }
            
            .card-title {
                margin-bottom: 0.5rem;
                font-size: 1.1rem;
            }
        }
        
        .status-hijau { color: #198754 !important; }
        .status-kuning { color: #ffc107 !important; }
        .status-merah { color: #dc3545 !important; }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Responsive Styles */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1030;
                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            /* Form improvements for mobile */
            .form-group, .mb-3 {
                margin-bottom: 1rem !important;
            }
            
            .form-label {
                margin-bottom: 0.375rem;
                font-weight: 500;
            }
            
            /* Improve spacing in tables */
            .table {
                width: 100%;
                margin-bottom: 0.75rem;
            }
            
            /* Improve card spacing */
            .card {
                margin-bottom: 1rem;
            }
            
            /* Improve button spacing */
            .btn-toolbar .btn {
                margin-bottom: 0.5rem;
                margin-right: 0.5rem;
            }
            
            /* Improve pagination on mobile */
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .pagination .page-item {
                margin-bottom: 0.25rem;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.75rem;
                min-width: 2.25rem;
                text-align: center;
            }
            
            /* Improve alerts on mobile */
            .alert {
                display: flex;
                align-items: flex-start;
            }
            
            .alert .btn-close {
                margin-left: auto;
                padding: 0.75rem 1rem;
                margin: -0.75rem -1rem -0.75rem auto;
            }
            
            /* Improve modal on mobile */
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-content {
                border-radius: 0.5rem;
            }
            
            .modal-header {
                align-items: center;
            }
            
            .modal-title {
                font-size: 1.1rem;
                line-height: 1.5;
            }
            
            .modal-footer {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .modal-footer .btn {
                margin: 0.25rem;
                min-width: 5rem;
            }
        }
        
        /* Improve touch targets on mobile */
        @media (max-width: 575.98px) {
            .nav-link, .btn {
                padding: 0.5rem 0.75rem;
                margin-bottom: 0.25rem;
            }
            
            .btn-group > .btn {
                padding: 0.375rem 0.5rem;
            }
            
            .form-control, .form-select {
                font-size: 16px; /* Prevents iOS zoom on focus */
                height: calc(1.5em + 0.75rem + 6px);
                padding: 0.5rem 0.75rem;
            }
            
            /* Improve form layout on mobile */
            .row > [class*="col-"] {
                margin-bottom: 1rem;
            }
            
            /* Improve form controls spacing */
            .form-check {
                margin-bottom: 0.5rem;
                padding-left: 1.75rem;
            }
            
            .form-check-input {
                margin-left: -1.75rem;
            }
            
            /* Improve file input on mobile */
            .form-control-file, .form-control[type="file"] {
                padding: 0.375rem 0.5rem;
            }
            
            h1, .h1 { font-size: 1.75rem; }
            h2, .h2 { font-size: 1.5rem; }
            h3, .h3 { font-size: 1.25rem; }
            
            .table th, .table td {
                padding: 0.5rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            .modal-header, .modal-footer {
                padding: 0.75rem 1rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .alert {
                padding: 0.75rem 1rem;
            }
        }
        
        /* Tablet improvements */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .card-body {
                padding: 1.25rem;
            }
            
            .table th, .table td {
                padding: 0.625rem;
            }
            
            .btn-group-sm > .btn, .btn-sm {
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <div id="app">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script>
        // Global functions
        function showLoading() {
            $('.loading').removeClass('d-none');
        }
        
        function hideLoading() {
            $('.loading').addClass('d-none');
        }
        
        function showAlert(type, message) {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Berhasil!' : 'Gagal!',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
        
        function confirmDelete(url, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
        
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Mobile sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const sidebar = document.querySelector('.sidebar');
                const toggleButton = document.querySelector('[data-bs-target=".sidebar"]');
                
                if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('show')) {
                    // Check if click is outside sidebar and not on toggle button
                    if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
                        const bsCollapse = new bootstrap.Collapse(sidebar);
                        bsCollapse.hide();
                    }
                }
            });
            
            // Close sidebar when clicking on a nav link on mobile
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        const sidebar = document.querySelector('.sidebar');
                        if (sidebar && sidebar.classList.contains('show')) {
                            const bsCollapse = new bootstrap.Collapse(sidebar);
                            bsCollapse.hide();
                        }
                    }
                });
            });
            
            // Adjust main content padding on mobile
            function adjustLayout() {
                const main = document.querySelector('main');
                if (main) {
                    if (window.innerWidth < 576) {
                        main.classList.add('pt-2');
                    } else {
                        main.classList.remove('pt-2');
                    }
                }
                
                // Add table-responsive class to tables if not already added
                const tables = document.querySelectorAll('table:not(.table-responsive)');
                if (window.innerWidth < 992) {
                    tables.forEach(table => {
                        if (!table.parentElement.classList.contains('table-responsive')) {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('table-responsive');
                            table.parentNode.insertBefore(wrapper, table);
                            wrapper.appendChild(table);
                        }
                    });
                }
                
                // Adjust form layout on smaller screens
                if (window.innerWidth < 768) {
                    // Make sure labels are visible on small screens
                    const formLabels = document.querySelectorAll('.col-form-label');
                    formLabels.forEach(label => {
                        label.classList.add('form-label');
                        label.classList.remove('col-form-label');
                    });
                }
            }
            
            // Run on load and resize
            adjustLayout();
            window.addEventListener('resize', adjustLayout);
            
            // Add swipe hint for tables on mobile
            if (window.innerWidth < 768) {
                const tableResponsives = document.querySelectorAll('.table-responsive');
                tableResponsives.forEach(tableWrap => {
                    if (tableWrap.scrollWidth > tableWrap.clientWidth) {
                        const hint = document.createElement('div');
                        hint.className = 'text-center text-muted small py-2';
                        hint.innerHTML = '<i class="fas fa-arrows-left-right me-1"></i> Geser untuk melihat seluruh tabel';
                        tableWrap.parentNode.insertBefore(hint, tableWrap.nextSibling);
                        
                        // Remove hint after user has scrolled
                        tableWrap.addEventListener('scroll', function() {
                            if (hint.parentNode) {
                                hint.parentNode.removeChild(hint);
                            }
                        }, { once: true });
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>