<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\FieldController as UserFieldController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Semua route web aplikasi futsal booking
| Dibagi jadi 3 bagian: public, admin, dan user
*/

// Halaman utama langsung redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (login, register, dll)
// Pake rate limit biar ga bisa brute force (max 5x per menit)
Route::middleware(['throttle:5,1'])->group(function () {
    Auth::routes();
});

// Dashboard setelah login
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Semua route khusus admin, butuh login + role admin
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/calendar', [AdminBookingController::class, 'calendar'])->name('bookings.calendar');
        Route::get('/calendar/bookings', [AdminBookingController::class, 'getCalendarBookings'])->name('bookings.calendar.data');

        // Kelola users, fields, bookings (CRUD lengkap)
        Route::resource('users', UserController::class);
        Route::resource('fields', AdminFieldController::class);
        Route::resource('bookings', AdminBookingController::class);

        // Tandai booking selesai
        Route::patch('bookings/{booking}/complete', [AdminBookingController::class, 'complete'])
            ->name('bookings.complete');
    });

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
| Route khusus user biasa, butuh login + role user
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        // Dashboard user
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        // Kalender booking
        Route::get('/calendar', [UserBookingController::class, 'calendar'])->name('bookings.calendar');
        Route::get('/calendar/bookings', [UserBookingController::class, 'getCalendarBookings'])->name('bookings.calendar.data');
        
        // Kelola booking sendiri
        Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [UserBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [UserBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
        Route::delete('/bookings/{booking}', [UserBookingController::class, 'destroy'])->name('bookings.destroy');

        // Lihat daftar lapangan
        Route::get('/fields', [UserFieldController::class, 'index'])->name('fields.index');
    });
