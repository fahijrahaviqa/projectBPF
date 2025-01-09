@extends('layouts.app')

@section('content')
<div class="container-xxl position-relative p-0">
    <!-- Hero Start -->
    <div class="container-xxl py-5 bg-dark hero-header mb-0">
        <div class="container text-center my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Reset Password</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('index.index') }}">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Reset Password</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Form Start -->
    <div class="container-xxl bg-dark py-5" style="margin-bottom: -5rem;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-9 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-dark text-center rounded p-4" style="max-width: 400px; margin: 0 auto;">
                        <h1 class="text-white mb-4">Reset Password</h1>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                            id="email" name="email" placeholder="Email Address"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus
                                            style="height: 50px;">
                                        <label for="email">Email Address</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-2" type="submit">
                                        Send Password Reset Link
                                    </button>
                                </div>
                                <div class="col-12 mt-3">
                                    <p class="text-white mb-0">Remember your password? 
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
