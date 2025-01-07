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
                    <img class="img-fluid" src="/assets/img/hero.png" alt="">
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
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">About Us</h5>
                <h1 class="mb-4">Welcome to <i class="fa fa-1x fa-solid fa-bowl-food text-primary mb-4"></i>
                    Bakso Soponyono Cak Agus</h1>
                <p class="mb-4">Bakso Soponyono adalah bakso khas Malang yang nggak sekadar makanan, tapi
                    juga nostalgia. Dari bakso urat biasa sampai bakso jumbo dengan kuah yang gurih, semua
                    dibuat dari resep keluarga yang bikin kamu inget suasana kota Malang.</p>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">10
                            </h1>
                            <div class="ps-4">
                                <p class="mb-0">Years of</p>
                                <h6 class="text-uppercase mb-0">Experience</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">50
                            </h1>
                            <div class="ps-4">
                                <p class="mb-0">Popular</p>
                                <h6 class="text-uppercase mb-0">Master Chefs</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary py-3 px-5 mt-2" href="{{ route('about.index') }}">Our Story</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->


<!-- Menu Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
            <h1 class="mb-5">Most Popular Items</h1>
        </div>
        <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.1s">
            <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active"
                        data-bs-toggle="pill" href="#tab-1">
                        <i class="fa fa-coffee fa-2x text-primary"></i>
                        <div class="ps-3">
                            <small class="text-body">Menu</small>
                            <h6 class="mt-n1 mb-0">Makanan</h6>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="d-flex align-items-center text-start mx-3 pb-3" data-bs-toggle="pill"
                        href="#tab-2">
                        <i class="fa fa-hamburger fa-2x text-primary"></i>
                        <div class="ps-3">
                            <small class="text-body">Menu</small>
                            <h6 class="mt-n1 mb-0">Minuman</h6>
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


<!-- Reservation Start -->
<div class="container-xxl py-5 px-0 wow fadeInUp" data-wow-delay="0.1s">
    <div class="row g-0">
        <div class="col-md-6 bg-dark d-flex align-items-center">
            <div class="p-5 wow fadeInUp" data-wow-delay="0.2s">
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Booking</h5>
                <center><h1 class="text-white mb-4">Pesan Meja Sekarang!</h1></center>
                <form>
                    <div class="row g-3">
                        <div class="col-md-66">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" placeholder="Your Name">
                                <label for="name">Masukkan Nama</label>
                            </div>
                        </div>
                        <div class="col-md-66">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" placeholder="Your Email">
                                <label for="email">Masukkan Email </label>
                            </div>
                        </div>
                        <div class="col-md-66">
                            <div class="form-floating date" id="date3" data-target-input="nearest">
                                <input type="date" class="form-control datetimepicker-input" id="datetime" placeholder="Date & Time" data-target="#date3" data-toggle="datetimepicker" />
                                <label for="datetime">Tanggal</label>
                            </div>
                        </div>
                        <div class="col-md-66">
                            <div class="form-floating date" id="date4" data-target-input="nearest">
                                <input type="time" class="form-control datetimepicker-input" id="datetime" placeholder="Time" data-target="#date4" data-toggle="datetimepicker" />
                                <label for="time">Waktu</label>
                            </div>
                        </div>
                        <div class="col-md-66">
                            <div class="form-floating">
                                <input list="people-options" class="form-control" id="people-input" placeholder="Masukkan jumlah orang">
                                <datalist id="people-options">
                                </datalist>
                                <label for="people-input">Jumlah Orang</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Special Request" id="message" style="height: 100px"></textarea>
                                <label for="message">Special Request</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">Book Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- 16:9 aspect ratio -->
                <div class="ratio ratio-16x9">
                    <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                        allow="autoplay"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Reservation Start -->


<!-- Team Start -->
<div class="container-xxl pt-5 pb-3">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Team Members</h5>
            <h1 class="mb-5">Our Master Chefs</h1>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item text-center rounded overflow-hidden">
                    <div class="rounded-circle overflow-hidden m-4">
                        <img class="img-fluid" src="/assets/img/team-1.jpg" alt="">
                    </div>
                    <h5 class="mb-0">Full Name</h5>
                    <small>Designation</small>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="team-item text-center rounded overflow-hidden">
                    <div class="rounded-circle overflow-hidden m-4">
                        <img class="img-fluid" src="/assets/img/team-2.jpg" alt="">
                    </div>
                    <h5 class="mb-0">Full Name</h5>
                    <small>Designation</small>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="team-item text-center rounded overflow-hidden">
                    <div class="rounded-circle overflow-hidden m-4">
                        <img class="img-fluid" src="/assets/img/team-3.jpg" alt="">
                    </div>
                    <h5 class="mb-0">Full Name</h5>
                    <small>Designation</small>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="team-item text-center rounded overflow-hidden">
                    <div class="rounded-circle overflow-hidden m-4">
                        <img class="img-fluid" src="/assets/img/team-4.jpg" alt="">
                    </div>
                    <h5 class="mb-0">Full Name</h5>
                    <small>Designation</small>
                    <div class="d-flex justify-content-center mt-3">
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-primary mx-1" href=""><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Team End -->

<!-- Testimonial Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Testimonial</h5>
            <h1 class="mb-5">Our Clients Say!!!</h1>
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


<!-- Footer Start -->
<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Company</h4>
                <a class="btn btn-link" href="">About Us</a>
                <a class="btn btn-link" href="">Contact Us</a>
                <a class="btn btn-link" href="">Reservation</a>
                <a class="btn btn-link" href="">Privacy Policy</a>
                <a class="btn btn-link" href="">Terms & Condition</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-outline-light btn-social" href=""><i
                            class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i
                            class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i
                            class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i
                            class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
                <h5 class="text-light fw-normal">Monday - Saturday</h5>
                <p>09AM - 09PM</p>
                <h5 class="text-light fw-normal">Sunday</h5>
                <p>10AM - 08PM</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Newsletter</h4>
                <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                <div class="position-relative mx-auto" style="max-width: 400px;">
                    <input class="form-control border-primary w-100 py-3 ps-4 pe-5" type="text"
                        placeholder="Your email">
                    <button type="button"
                        class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved.

                    <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                    Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a><br><br>
                    Distributed By <a class="border-bottom" href="https://themewagon.com"
                        target="_blank">ThemeWagon</a>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <a href="">Home</a>
                        <a href="">Cookies</a>
                        <a href="">Help</a>
                        <a href="">FQAs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

@endsection