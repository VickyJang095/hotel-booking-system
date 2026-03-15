<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OwnerRequestController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Owner\OwnerController;
use Illuminate\Support\Facades\Auth;

// ── PUBLIC ──────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/auth/send-code',   [AuthController::class, 'sendOtp'])->name('auth.send-otp');
Route::post('/auth/verify-code', [AuthController::class, 'verifyOtp'])->name('auth.verify-code');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
Route::get('/login', fn() => redirect('/'))->name('login');

Route::get('/search',      [SearchController::class, 'search'])->name('hotels.search');
Route::get('/hotels/{id}', [HotelController::class,  'show'])->name('hotels.show');

// ── BOOKING ─────────────────────────────────────────────────
Route::get('/hotels/{id}/booking', [BookingController::class, 'showDetails'])->name('booking.details');
Route::get('/hotels/{id}/payment', [BookingController::class, 'showPayment'])->name('booking.payment');

// ── ĐĂNG KÝ HOTEL OWNER (user đã login) ─────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/become-owner',  [OwnerRequestController::class, 'create'])->name('owner-request.create');
    Route::post('/become-owner', [OwnerRequestController::class, 'store'])->name('owner-request.store');
});

// ── ADMIN ────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard',        [AdminController::class, 'dashboard'])->name('dashboard');

    // Hotels CRUD + duyệt
    Route::get('/hotels',               [AdminController::class, 'hotels'])->name('hotels');
    Route::get('/hotels/create',        [AdminController::class, 'createHotel'])->name('hotels.create');
    Route::post('/hotels',              [AdminController::class, 'storeHotel'])->name('hotels.store');
    Route::get('/hotels/{id}/edit',     [AdminController::class, 'editHotel'])->name('hotels.edit');
    Route::put('/hotels/{id}',          [AdminController::class, 'updateHotel'])->name('hotels.update');
    Route::delete('/hotels/{id}',       [AdminController::class, 'deleteHotel'])->name('hotels.delete');
    Route::post('/hotels/{id}/approve', [AdminController::class, 'approveHotel'])->name('hotels.approve');
    Route::post('/hotels/{id}/reject',  [AdminController::class, 'rejectHotel'])->name('hotels.reject');

    // Users
    Route::get('/users',               [AdminController::class, 'users'])->name('users');
    Route::put('/users/{id}/role',     [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::delete('/users/{id}',       [AdminController::class, 'deleteUser'])->name('users.delete');

    // Owner requests
    Route::get('/owner-requests',                    [AdminController::class, 'ownerRequests'])->name('owner-requests');
    Route::post('/owner-requests/{id}/approve',      [AdminController::class, 'approveOwnerRequest'])->name('owner-requests.approve');
    Route::post('/owner-requests/{id}/reject',       [AdminController::class, 'rejectOwnerRequest'])->name('owner-requests.reject');

    // Bookings
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
});

// ── HOTEL OWNER ──────────────────────────────────────────────
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:hotel_owner'])->group(function () {
    Route::get('/dashboard',        [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/hotel/edit',       [OwnerController::class, 'editHotel'])->name('hotel.edit');
    Route::put('/hotel',            [OwnerController::class, 'updateHotel'])->name('hotel.update');
    Route::get('/bookings',         [OwnerController::class, 'bookings'])->name('bookings');
    Route::get('/rooms',            [OwnerController::class, 'rooms'])->name('rooms');
    Route::get('/rooms/create',     [OwnerController::class, 'createRoom'])->name('rooms.create');
    Route::post('/rooms',           [OwnerController::class, 'storeRoom'])->name('rooms.store');
    Route::get('/rooms/{id}/edit',  [OwnerController::class, 'editRoom'])->name('rooms.edit');
    Route::put('/rooms/{id}',       [OwnerController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{id}',    [OwnerController::class, 'deleteRoom'])->name('rooms.delete');
});
