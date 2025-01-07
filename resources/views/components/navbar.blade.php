<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
    <a href="" class="navbar-brand p-0">
        <h1 class="text-primary m-0"><img src="/assets/img/bakso.png"></i></h1>
        <!-- <img src="/assets/img/logo.png" alt="Logo"> -->
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto py-0 pe-4">
            <a href="{{ route('index.index') }}" class="nav-item nav-link {{ request()->routeIs('index.*') ? 'active' : '' }}">Home</a>
            <a href="{{ route('menu.index') }}" class="nav-item nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">Menu</a>
            <div class="nav-item dropdown">
                <a href="{{ route('service.index') }}" class="nav-link dropdown-toggle {{ request()->routeIs('service.*') || request()->routeIs('pesan.*') || request()->routeIs('preorder.*') || request()->routeIs('booking.*') ? 'active' : '' }}" data-bs-toggle="dropdown">Layanan</a>
                <div class="dropdown-menu m-0">
                    <a href="{{ route('pesan.index') }}" class="dropdown-item {{ request()->routeIs('pesan.*') ? 'active' : '' }}">Pesan Antar</a>
                    <a href="{{ route('preorder.index') }}" class="dropdown-item {{ request()->routeIs('preorder.*') ? 'active' : '' }}">Pre-Order</a>
                    <a href="{{ route('booking.index') }}" class="dropdown-item {{ request()->routeIs('booking.*') ? 'active' : '' }}">Reservasi</a>
                </div>
            </div>
            <a href="{{ route('about.index') }}" class="nav-item nav-link {{ request()->routeIs('about.*') ? 'active' : '' }}">Tentang Kami</a>
            <a href="{{ route('testimonial.index') }}" class="nav-item nav-link {{ request()->routeIs('testimonial.*') ? 'active' : '' }}">Testimoni</a>
            <a href="{{ route('contact.index') }}" class="nav-item nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}">Kontak</a>
            @auth
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu m-0">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Dashboard Admin</a>
                        @else
                            <a href="{{ route('orders.index') }}" class="dropdown-item">Dashboard Customer</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
            @endauth
        </div>
    </div>
</nav>

@if(isset($showHero) && $showHero)
<div class="container-xxl py-5 bg-dark hero-header mb-5">
    <div class="container text-center my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">{{ $title ?? 'Welcome' }}</h1>
    </div>
</div>
@endif