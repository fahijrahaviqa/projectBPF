<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DriveThruController;
use App\Http\Controllers\Admin\TableController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReservationController;

// Public routes
Route::get('/', [MenuController::class, 'index']);
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/testimonial', [TestimonialController::class, 'landing'])->name('testimonial.index');

// Customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Order routes
    Route::resource('orders', OrderController::class);
    Route::patch('orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    
    // Booking routes
    Route::resource('booking', BookingController::class);
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
    
    // Menu Management
    Route::resource('menu', MenuItemController::class);
    Route::resource('categories', CategoryController::class);
    
    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');
    
    // Drive Thru
    Route::get('drive-thru', [DriveThruController::class, 'index'])->name('drive-thru.index');
    Route::get('drive-thru/create', [DriveThruController::class, 'create'])->name('drive-thru.create');
    Route::post('drive-thru', [DriveThruController::class, 'store'])->name('drive-thru.store');
    Route::get('drive-thru/{order}', [DriveThruController::class, 'show'])->name('drive-thru.show');
    Route::post('drive-thru/{order}/payment', [DriveThruController::class, 'updatePaymentStatus'])
        ->name('drive-thru.update-payment');
    
    // Content Management
    Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('testimonials/{testimonial}/publish', [TestimonialController::class, 'publish'])->name('testimonials.publish');
    Route::resource('team', TeamController::class);
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Table Management
    Route::resource('tables', TableController::class)->names('tables');
    Route::get('tables/{table}/view', [TableController::class, 'view'])->name('tables.view');
    Route::post('tables/{table}/status', [TableController::class, 'updateStatus'])->name('tables.update-status');
    Route::get('tables/{table}/reservations', [TableController::class, 'getReservations'])->name('tables.reservations');
    Route::get('tables/{table}/reservations/{date}', [TableController::class, 'getReservationsByDate'])->name('tables.reservations.date');
});

Auth::routes();

// Redirect after login based on role
Route::get('/home', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('orders.index');
})->name('home');


route::resource('index', IndexController::class);
route::resource('about', AboutController::class);
route::resource('booking', BookingController::class);
route::resource('contact', ContactController::class);
route::resource('menu', MenuController::class);
route::resource('service', ServiceController::class);
route::resource('team', TeamController::class);
route::resource('preorder', PreorderController::class);
route::resource('pesan', PesanController::class);

// Testimoni routes untuk user yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('testimonial/create', [TestimonialController::class, 'create'])->name('testimonial.create');
    Route::post('testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
    Route::get('testimonial/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonial.edit');
    Route::put('testimonial/{testimonial}', [TestimonialController::class, 'update'])->name('testimonial.update');
    Route::delete('testimonial/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('testimonial/create', [TestimonialController::class, 'create'])->name('testimonial.create');
    Route::post('testimonial', [TestimonialController::class, 'store'])->name('testimonial.store');
    Route::get('testimonial/{testimonial}', [TestimonialController::class, 'show'])->name('testimonial.show');
    Route::get('testimonial/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonial.edit');
    Route::put('testimonial/{testimonial}', [TestimonialController::class, 'update'])->name('testimonial.update');
    Route::delete('testimonial/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');
    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/status/{status}', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Reservations
    Route::resource('reservations', ReservationController::class);
    Route::post('reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    Route::post('reservations/{reservation}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');
    Route::post('reservations/{reservation}/complete', [ReservationController::class, 'complete'])->name('reservations.complete');
});
