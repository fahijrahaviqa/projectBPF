@extends('layouts.main')

@section('title', 'Testimoni')

@php
$showHero = true;
$heroTitle = 'Testimonial';
@endphp

@section('content')
<!-- Testimonial Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Testimonial</h5>
            <h1 class="mb-5">Apa Kata Mereka?</h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            @foreach($testimonials as $testimonial)
            <div class="testimonial-item bg-transparent border rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>{{ $testimonial->content }}</p>
                <div class="d-flex align-items-center">
                    @if($testimonial->image)
                        <img class="img-fluid flex-shrink-0 rounded-circle" src="{{ asset('storage/' . $testimonial->image) }}" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        @php
                            $name = $testimonial->order->user->name;
                            $initials = strtoupper(substr($name, 0, 2));
                        @endphp
                        <div class="profile-initial flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px;">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="ps-3">
                        <h5 class="mb-1">{{ $testimonial->order->user->name }}</h5>
                        <div class="text-primary mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <small>{{ $testimonial->title }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @auth
            <div class="text-center mt-5">
                <a href="{{ route('testimonial.create') }}" class="btn btn-primary">Berikan Testimoni</a>
            </div>
        @else
            <div class="text-center mt-5">
                <p>Ingin memberikan testimoni? Silakan <a href="{{ route('login') }}">login</a> terlebih dahulu.</p>
            </div>
        @endauth
    </div>
</div>
<!-- Testimonial End -->
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
.profile-initial {
    font-size: 1.2rem;
    font-weight: bold;
    font-family: 'Nunito', sans-serif;
}
</style>
@endpush