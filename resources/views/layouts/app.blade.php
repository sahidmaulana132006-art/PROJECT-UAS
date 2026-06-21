<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIEK')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563EB;
            --primary-hover: #1D4ED8;
            --secondary-color: #0F172A;
            --success-color: #22C55E;
            --danger-color: #EF4444;
            --bg-color: #F8FAFC;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: #334155;
            overflow-x: hidden;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--secondary-color);
            color: #f1f5f9;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }

        #sidebar .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
        }

        #sidebar .sidebar-menu {
            padding: 1.5rem 0.75rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        #sidebar .sidebar-menu::-webkit-scrollbar { width: 5px; }
        #sidebar .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        #sidebar .nav-link {
            color: #94a3b8;
            padding: 0.8rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }

        #sidebar .nav-link i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
            transition: all 0.2s;
        }

        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }

        #sidebar .nav-link.active {
            background-color: var(--primary-color) !important;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4);
        }

        #sidebar .nav-link.active i { color: white; }

        #main-content {
            @auth margin-left: var(--sidebar-width); @else margin-left: 0; @endauth
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 990;
        }

        body.sidebar-toggled #sidebar { transform: translateX(-100%); }
        body.sidebar-toggled #main-content { margin-left: 0; }

        #sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(2px);
            z-index: 995;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }

        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #main-content { margin-left: 0; }
            body.sidebar-toggled #sidebar { transform: translateX(0); }
            body.sidebar-toggled #main-content { margin-left: 0; }
            body.sidebar-toggled #sidebar-overlay { opacity: 1; visibility: visible; }
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
            background-color: #ffffff;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body { padding: 1.5rem; }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .breadcrumb-item a { color: var(--primary-color); text-decoration: none; font-weight: 500; }
        .badge-pending { background-color: #FEF3C7; color: #D97706; padding: 0.4rem 0.8rem; border-radius: 50rem; font-weight: 600; font-size: 0.8rem; display: inline-block; }
        .badge-success { background-color: #DCFCE7; color: #16A34A; padding: 0.4rem 0.8rem; border-radius: 50rem; font-weight: 600; font-size: 0.8rem; display: inline-block; }
        .badge-danger { background-color: #FEE2E2; color: #DC2626; padding: 0.4rem 0.8rem; border-radius: 50rem; font-weight: 600; font-size: 0.8rem; display: inline-block; }
    </style>
    @yield('styles')
</head>
<body>

    @auth
    <div id="sidebar-overlay"></div>
    <div id="sidebar">
        <div class="sidebar-header">
            <span class="fs-4 fw-bold text-white d-flex align-items-center">
                <div class="p-1 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-mortarboard-fill text-primary fs-5"></i>
                </div>
                SIEK
            </span>
        </div>
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        <i class="bi bi-calendar-event-fill"></i> Event Kampus
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('registrations.*') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                        <i class="bi bi-clipboard-check-fill"></i> Pendaftaran
                    </a>
                </li>
                
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-tags-fill"></i> Kategori Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                            <i class="bi bi-credit-card-fill"></i> Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('users.*') && request('role') == 'panitia' ? 'active' : '' }}" href="{{ route('users.index', ['role' => 'panitia']) }}">
                            <i class="bi bi-people-fill"></i> Data Panitia
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('users.*') && request('role') == 'peserta' ? 'active' : '' }}" href="{{ route('users.index', ['role' => 'peserta']) }}">
                            <i class="bi bi-person-fill-gear"></i> Data Peserta
                        </a>
                    </li>
                @endif

                @if(auth()->user()->role === 'panitia')
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                            <i class="bi bi-qr-code-scan"></i> Absensi Peserta
                        </a>
                    </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('certificates.*') ? 'active' : '' }}" href="{{ route('certificates.index') }}">
                        <i class="bi bi-award-fill"></i> Sertifikat
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="p-3 mt-auto" style="background-color: rgba(0, 0, 0, 0.25); border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: 600; letter-spacing: 1px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                </div>
                <div class="flex-grow-1 ms-3 overflow-hidden">
                    <h6 class="mb-0 text-white text-truncate fw-semibold">{{ auth()->user()->name }}</h6>
                    <small class="text-info text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ auth()->user()->role }}</small>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <div id="main-content">
        <nav class="top-navbar shadow-sm">
            <div class="d-flex align-items-center">
                @auth
                <button class="btn btn-light border-0 me-3 text-secondary" id="sidebar-toggle" style="background: transparent;">
                    <i class="bi bi-list fs-4"></i>
                </button>
                @else
                <div class="p-1 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-mortarboard-fill text-primary fs-5"></i>
                </div>
                @endauth
                
                <div class="d-none d-md-block">
                    <h5 class="mb-0 fw-bold text-dark">Sistem Informasi Event Kampus</h5>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                @auth
                <div class="dropdown">
                    <button class="btn btn-light border-0 rounded-pill d-flex align-items-center px-3 py-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #f8fafc;">
                        <i class="bi bi-person-circle fs-5 text-primary me-2"></i> 
                        <span class="fw-medium text-dark">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item py-2 px-3 fw-medium" href="{{ route('profile.edit') }}">
                                <i class="bi bi-gear text-secondary me-2"></i> Pengaturan Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider opacity-25"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2 px-3 fw-medium w-100 border-0 bg-transparent text-start">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-medium shadow-sm">
                    Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
                </a>
                @endauth
            </div>
        </nav>

        <div class="container-fluid p-4 flex-grow-1">
            @auth
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white px-3 py-2 rounded-pill shadow-sm d-inline-flex border">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
            @endauth

            @yield('content')
        </div>

        <footer class="bg-white border-top py-4 text-center text-muted mt-auto">
            <div class="container">
                <small class="fw-medium">&copy; 2026 SIEK. All rights reserved.</small>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const overlay = document.getElementById('sidebar-overlay');
        const body = document.body;

        if(sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                body.classList.toggle('sidebar-toggled');
            });
        }

        if(overlay) {
            overlay.addEventListener('click', () => {
                body.classList.remove('sidebar-toggled');
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                customClass: { popup: 'rounded-4' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                timer: 4000,
                showConfirmButton: true,
                confirmButtonColor: '#2563EB',
                customClass: { confirmButton: 'rounded-pill px-4' }
            });
        @endif
    </script>
    @yield('scripts')
</body>
</html>