<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

 
 Route::get('/', function () {
        return Inertia::render('Home');
    });

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register/step-1', [AuthController::class, 'registerStep1'])->middleware('throttle:3,1');
    Route::post('/register/step-2', [AuthController::class, 'registerStep2'])->middleware(['register.step:otp', 'throttle:5,1']);
    Route::post('/register/resend-otp', [AuthController::class, 'resendRegisterOtp'])->middleware(['register.step:otp', 'throttle:3,1']);
    Route::post('/register/step-3', [AuthController::class, 'registerStep3'])->middleware('register.step:password');
    Route::post('/register/step-4', [AuthController::class, 'registerStep4'])->middleware('register.step:profile');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgot'])->name('forgot.password');
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->middleware('throttle:3,1');
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->middleware(['forgot.step:otp', 'throttle:5,1']);
    Route::post('/forgot-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->middleware(['forgot.step:otp', 'throttle:3,1']);
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->middleware('forgot.step:reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});
