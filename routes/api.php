<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::get('/ping', function () {
    return response()->json(['status' => 'ok']);
});
Route::post('auth/send-otp', [AuthController::class, 'sendOtp']) -> name('send.otp');
Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp']) -> name('verify.otp');