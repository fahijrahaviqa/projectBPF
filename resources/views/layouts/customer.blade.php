<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Bakso Soponyono') }}</title>

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
        }
        .sidebar {
            min-height: 100vh;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding-bottom: 60px;
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
        /* Hide scrollbar for Chrome, Safari and Opera */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
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
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="bg-dark sidebar" style="width: 250px;">
        <div class="position-sticky">
            <div class="px-3 py-4 d-flex align-items-center border-bottom border-secondary">
                <img src="{{ asset('assets/img/bakso.png') }}" alt="Logo" height="40" class="me-2">
                <div>
                    <h6 class="text-white mb-0">Menu Customer</h6>
                    <small class="text-white-50">{{ auth()->user()->name }}</small>
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="nav-heading">Menu Utama</div>
            <ul class="nav flex-column">
                <!-- Menu -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('menu*') ? 'active' : '' }}" 
                       href="{{ route('menu.index') }}">
                        <i class="fas fa-utensils me-2"></i>
                        Menu
                    </a>
                </li>

                <!-- Order Management -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('orders*') ? 'active' : '' }}" 
                       href="{{ route('orders.index') }}">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Pesanan Saya
                    </a>
                </li>

                <!-- Booking -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}" 
                       href="{{ route('reservations.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Reservasi Meja
                    </a>
                </li>
            </ul>

            <!-- Information -->
            <div class="nav-heading">Informasi</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about*') ? 'active' : '' }}" 
                       href="{{ route('about.index') }}">
                        <i class="fas fa-info-circle me-2"></i>
                        Tentang Kami
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" 
                       href="{{ route('contact.index') }}">
                        <i class="fas fa-envelope me-2"></i>
                        Kontak
                    </a>
                </li>
            </ul>

            <!-- User Menu -->
            <div class="nav-heading">Pengaturan</div>
            <ul class="nav flex-column mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" 
                       href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-cog me-2"></i>
                        Profil Saya
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