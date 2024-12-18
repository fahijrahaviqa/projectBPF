<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\indexController;
use App\Http\Controllers\aboutController;
use App\Http\Controllers\bookingController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\serviceController;
use App\Http\Controllers\teamController;
use App\Http\Controllers\testimonialController;



Route::get('/', function () {
    return view('menu');
});

route::resource('index', indexController::class);
route::resource('about', aboutController::class);
route::resource('booking', bookingController::class);
route::resource('contact', contactController::class);
route::resource('menu', menuController::class);
route::resource('service', serviceController::class);
route::resource('team', teamController::class);
route::resource('testimonial', testimonialController::class);