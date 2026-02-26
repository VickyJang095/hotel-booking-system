<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/auth/send-code', [AuthController::class, 'sendOtp'])
    ->name('auth.send-otp');

Route::post('/auth/verify-code', [AuthController::class, 'verifyOtp'])
    ->name('auth.verify-code');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');


Route::get('/login', function () {
    return redirect('/');
})->name('login');
