<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'EMKL Automation') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #2563EB;
            --secondary-color: #1E293B;
            --accent-color: #06B6D4;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --bg-color: #F8FAFC;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--secondary-color);
            overflow-x: hidden;
        }

        /* Sidebar Style */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--secondary-color);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }

        #sidebar .logo {
            padding: 2rem 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        #sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
            border-left-color: var(--primary-color);
        }

        #sidebar .nav-link i {
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        /* Main Content Style */
        #content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
        }

        .topbar {
            height: 70px;
            background: white;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="sidebar">
        <a href="{{ route('dashboard') }}" class="logo">
            <i class="bi bi-ship-wheel me-2 text-primary"></i>
            <span>PT. <span class="text-primary text-opacity-75">CARGOTAMA</span></span>
        </a>
        <nav class="nav flex-column mt-4">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Beranda
            </a>
            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                <i class="bi bi-people-fill"></i> Pelanggan
            </a>
            <a class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}" href="{{ route('shipments.index') }}">
                <i class="bi bi-box-seam-fill"></i> Pengiriman
            </a>
            <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                <i class="bi bi-file-earmark-text-fill"></i> Dokumen
            </a>
            <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                <i class="bi bi-receipt-cutoff"></i> Invoice
            </a>
            <a class="nav-link {{ request()->routeIs('receivables.*') ? 'active' : '' }}" href="{{ route('receivables.index') }}">
                <i class="bi bi-wallet2"></i> Piutang
            </a>
            <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                <i class="bi bi-bell-fill"></i> Notifikasi
            </a>
            <hr class="mx-3 opacity-10">
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-bar-chart-fill"></i> Laporan
            </a>
            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>
            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-person-circle"></i> Pengguna
            </a>
        </nav>
    </div>

    <div id="content">
        <header class="topbar justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-secondary d-md-none me-2" id="sidebarCollapse">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <div class="search-bar d-none d-md-flex">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control bg-light border-0" placeholder="Cari Global...">
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown me-3">
                    <button class="btn btn-link text-secondary position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 300px;">
                        <li class="p-3 border-bottom"><h6 class="mb-0">Notifikasi</h6></li>
                        <li><a class="dropdown-item p-3" href="#">INV-001 telah jatuh tempo</a></li>
                        <li><a class="dropdown-item p-3" href="#">Pengiriman baru BOOK-2026</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn d-flex align-items-center border-0 p-0" data-bs-toggle="dropdown">
                        <div class="text-end me-2 d-none d-sm-block">
                            <div class="fw-bold small">{{ Auth::user()->name ?? 'Admin' }}</div>
                            <div class="text-muted small" style="font-size: 0.7rem;">{{ Auth::user()->role->name ?? 'Administrator' }}</div>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=2563EB&color=fff" class="rounded-circle" width="38">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="d-none">@csrf</form>
                            <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="this.parentElement.remove()"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.parentElement.remove()"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>
