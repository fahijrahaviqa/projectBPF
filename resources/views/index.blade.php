@extends('layouts.main')

@section('title', 'Home')

@section('content')
<!-- Navbar & Hero Start -->
<!-- Navbar & Hero Start -->
<div class="container-xxl position-relative p-0">

    <div class="container-xxl py-5 bg-dark hero-header mb-5">
        <div class="container my-5 py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="display-3 text-white animated slideInLeft">Pemesanan Bakso Yang Praktis</h1>
                    <p class="mb-2"></i>Pesan Bakso Soponyono Favorit Anda hanya dengan beberapa klik! Nikmati
                        berbagai pilihan menu, layanan Pre-Order, dan pengantaran langsung ke rumah Anda. Kami
                        hadir untuk mempermudah Anda mendapatkan bakso lezat tanpa repot</p>
                    <a href="{{ route('menu.index') }}"
                        class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Lihat Menu</a>
                </div>
                <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                    <img class="img-fluid" src="/assets/img/bbb.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Navbar & Hero End -->


<!-- Service Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-33 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-utensils text-primary mb-4"></i>
                        <div>
                            <h5>Kami persembahkan menu spesial terbaru: <strong>Bakso Iga!</strong></h5>
                            <p>✨ <strong>Keunggulan Menu:</strong></p>
                            <ul>
                                <li><strong>Bakso Jumbo:</strong> Tekstur lembut yang memanjakan lidah.</li>
                                <li><strong>Iga Sapi:</strong> Potongan juicy yang kaya rasa.</li>
                                <li><strong>Kuah Kaldu Khas Malang:</strong> Gurih dan beraroma khas.</li>
                            </ul>
                            <p>🎯 <strong>Tersedia mulai:</strong></p>
                            <ul>
                                <li>📅 <strong>1 Januari 2025</strong></li>
                                <li>📍 <strong>Semua Outlet Kami</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-33 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="service-item rounded pt-3">
                    <div class="p-4">
                        <i class="fa fa-3x fa-store text-primary mb-4"></i>
                        <div>
                            <h5>🎉 <strong>Launching Outlet Baru</strong> 🎉</h5>
                            <p>✨ Kami telah membuka cabang baru di <strong>Panam</strong>!</p>
                            <p>🎯 Ayo kunjungi dan nikmati pengalaman terbaik kami di lokasi baru ini.</p>
                        </div>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31917.50895707572!2d101.34188037910157!3d0.4619908999999964!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5a842d44499c5%3A0x90b4b00ee537c3c9!2sBakso%20Sopo%20Nyono%20%22Cak%20Agus%203%22!5e0!3m2!1sen!2sid!4v1734676900617!5m2!1sen!2sid"
                            width="570" height="150" style="border:0;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Service End -->


<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s"
                            src="/assets/img/warung1.jpeg">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s"
                            src="/assets/img/warung2.jpeg" style="margin-top: -10%;">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s"
                            src="/assets/img/warung3.jpeg">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s"
                            src="/assets/img/warung4.jpeg">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Tentang Kami</h5>
                <h1 class="mb-4">Selamat Datang <i class="fa fa-1x fa-solid fa-bowl-food text-primary mb-4"></i>
                    Bakso Soponyono Cak Agus</h1>
                <p class="mb-4">Bakso Soponyono adalah bakso khas Malang yang nggak sekadar makanan, tapi
                    juga nostalgia. Dari bakso urat biasa sampai bakso jumbo dengan kuah yang gurih, semua
                    dibuat dari resep keluarga yang bikin kamu inget suasana kota Malang.</p>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">12
                            </h1>
                            <div class="ps-4">
                                <p class="mb-0">Tahun</p>
                                <h6 class="text-uppercase mb-0">Berpengalaman</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary py-3 px-5 mt-2" href="{{ route('about.index') }}">Perjalanan Kami</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->


<!-- Menu Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal"> Menu Pilihan </h5>
            <h1 class="mb-5">Pilih Menu, Nikmati Rasanya!</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill" href="#menu-makanan">
                        <i class="fa fa-hamburger fa-2x text-primary"></i>
                        <div class="ps-3">
                            <h6 class="mt-n1 mb-0">Makan</h6>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill" href="#menu-minuman">
                        <i class="fa fa-coffee fa-2x text-primary"></i>
                        <div class="ps-3">
                            <h6 class="mt-n1 mb-0">Minum</h6>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="menu-makanan" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        @foreach($menuMakanan as $menu)
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('storage/' . $menu->image) }}" alt="" style="width: 80px;">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>{{ $menu->name }}</span>
                                        <span class="text-primary">{{ number_format($menu->price, 0, ',', '.') }}</span>
                                    </h5>
                                    <small class="fst-italic">{{ $menu->description }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div id="menu-minuman" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        @foreach($menuMinuman as $menu)
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 img-fluid rounded" src="{{ asset('storage/' . $menu->image) }}" alt="" style="width: 80px;">
                                <div class="w-100 d-flex flex-column text-start ps-4">
                                    <h5 class="d-flex justify-content-between border-bottom pb-2">
                                        <span>{{ $menu->name }}</span>
                                        <span class="text-primary">{{ number_format($menu->price, 0, ',', '.') }}</span>
                                    </h5>
                                    <small class="fst-italic">{{ $menu->description }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Menu End -->

<!-- Testimonial Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Rating dan Ulasan</h5>
            <h1 class="mb-5">Apa Kata Mereka!!!</h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            @foreach($testimoni as $testimoni)
            <div class="testimonial-item bg-transparent border rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>{{ $testimoni->content }}</p>
                <div class="d-flex align-items-center">
                    @if($testimoni->image)
                        <img class="img-fluid flex-shrink-0 rounded-circle" src="{{ asset('storage/' . $testimoni->image) }}" style="width: 50px; height: 50px;">
                    @else
                        @php
                            $name = $testimoni->order->user->name;
                            $initials = strtoupper(substr($name, 0, 2));
                        @endphp
                        <div class="profile-initial flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px;">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="ps-3">
                        <h5 class="mb-1">{{ $testimoni->order->user->name }}</h5>
                        <small>{{ $testimoni->rating }} / 5</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Testimonial End -->



@endsection