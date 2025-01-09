@extends('layouts.main')

@section('title', 'Menu')

@php
$showHero = true;
$heroTitle = 'Menu';
@endphp

@section('content')
<!-- Menu Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Pilihan Menu</h5>
            <h1 class="mb-5">Pilih Menu, Nikmati Rasanya</h1>
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
@endsection