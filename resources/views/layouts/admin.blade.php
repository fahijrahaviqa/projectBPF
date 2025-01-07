<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Bakso Soponyono') }} - Admin Dashboard</title>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Customized Bootstrap Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #212529;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-content {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding-bottom: 60px;
            width: calc(100% - 250px);
        }
        .sidebar .nav-link {
            font-weight: 600;
            padding: .75rem 1rem;
            border-radius: .25rem;
            margin: 0.2rem 0.5rem;
            color: #fff;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, .2);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background-color: #FEA116;
            color: #000;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #212529;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        .alert {
            border-radius: 0.5rem;
        }
        .nav-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            padding: 0.75rem 1rem;
            margin-top: 1rem;
        }
        .nav-section {
            margin-bottom: 1rem;
        }
        .nav-section:last-child {
            margin-bottom: 2rem;
        }
        .sidebar-header {
            position: sticky;
            top: 0;
            background-color: #212529;
            z-index: 1020;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-header">
                <div class="px-3 py-4 d-flex align-items-center border-bottom border-secondary">
                    <img src="{{ asset('assets/img/bakso.png') }}" alt="Logo" height="40" class="me-2">
                    <div>
                        <h6 class="text-white mb-0">Admin Dashboard</h6>
                        <small class="text-white-50">{{ auth()->user()->name }}</small>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="nav-heading">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
            </ul>

            <!-- Menu Management -->
            <div class="nav-heading">Manajemen Menu</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/menu*') ? 'active' : '' }}" 
                       href="{{ route('admin.menu.index') }}">
                        <i class="fas fa-utensils me-2"></i>
                        Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}" 
                       href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-list me-2"></i>
                        Kategori
                    </a>
                </li>
            </ul>

            <!-- Order Management -->
            <div class="nav-heading">Manajemen Pesanan</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" 
                       href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/drive-thru*') ? 'active' : '' }}" 
                       href="{{ route('admin.drive-thru.index') }}">
                        <i class="fas fa-car me-2"></i>
                        Drive Thru
                    </a>
                </li>
            </ul>

            <!-- Table & Booking Management -->
            <div class="nav-heading">Manajemen Meja & Reservasi</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/tables*') ? 'active' : '' }}" 
                       href="{{ route('admin.tables.index') }}">
                        <i class="fas fa-chair me-2"></i>
                        Meja
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}" 
                       href="{{ route('reservations.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Reservasi
                    </a>
                </li>
            </ul>

            <!-- Content Management -->
            <div class="nav-heading">Manajemen Konten</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/testimonials*') ? 'active' : '' }}" 
                       href="{{ route('admin.testimonials.index') }}">
                        <i class="fas fa-star me-2"></i>
                        Testimoni
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/team*') ? 'active' : '' }}" 
                       href="{{ route('team.index') }}">
                        <i class="fas fa-users me-2"></i>
                        Tim
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/about*') ? 'active' : '' }}" 
                       href="{{ route('about.index') }}">
                        <i class="fas fa-info-circle me-2"></i>
                        Tentang Kami
                    </a>
                </li>
            </ul>

            <!-- User Menu -->
            <div class="nav-heading">Pengaturan</div>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" 
                       href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-user-cog me-2"></i>
                        Profil
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid py-4">

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
    
    @stack('scripts')
</body>
</html> 
</html> 