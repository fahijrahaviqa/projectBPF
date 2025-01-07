@extends('layouts.app')

@section('content')
<div class="container-xxl position-relative p-0">
    <!-- Hero Start -->
    <div class="container-xxl py-5 bg-dark hero-header mb-0">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Register</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('index.index') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Register</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Form Start -->
    <div class="container-xxl bg-dark py-5" style="margin-bottom: -5rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-dark text-center rounded p-5">
                        <h1 class="text-white mb-4">Buat Akun Baru</h1>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                            id="name" name="name" placeholder="Full Name"
                                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        <label for="name">Full Name</label>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" placeholder="Email Address"
                                            value="{{ old('email') }}" required autocomplete="email">
                                        <label for="email">Email Address</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                            id="password" name="password" placeholder="Password"
                                            required autocomplete="new-password">
                                        <label for="password">Password</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" 
                                            id="password-confirm" name="password_confirmation" 
                                            placeholder="Confirm Password" required autocomplete="new-password">
                                        <label for="password-confirm">Confirm Password</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Register</button>
                                </div>
                                <div class="col-12 mt-3">
                                    <p class="text-white">Already have an account? 
                                        <a href="{{ route('login') }}" class="text-primary">Login here</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form End -->
</div>
@endsection
