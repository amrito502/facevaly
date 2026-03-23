<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RoleUserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\SellerLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Seller\SellerDashboardController;

use App\Http\Controllers\Auth\SellerRegisterController;


Route::middleware('guest')->group(function () {
    Route::get('/become-seller', [SellerRegisterController::class, 'showRegister'])
        ->name('seller.register');

    Route::post('/become-seller/step-1', [SellerRegisterController::class, 'step1'])
        ->name('seller.register.step1');

    Route::post('/become-seller/resend-otp', [SellerRegisterController::class, 'resendOtp'])
        ->name('seller.register.resendOtp');

    Route::post('/become-seller/step-2', [SellerRegisterController::class, 'step2'])
        ->name('seller.register.step2');

    Route::post('/become-seller/step-3', [SellerRegisterController::class, 'step3'])
        ->name('seller.register.step3');

    Route::post('/become-seller/step-4', [SellerRegisterController::class, 'step4'])
        ->name('seller.register.step4');
});

Route::middleware('auth')->group(function () {
    Route::get('/seller/dashboard', function () {
        return Inertia::render('Seller/Dashboard');
    })->name('seller.dashboard');
});

// admin
Route::prefix('admin')->group(function () {
    Route::middleware(['guest', 'redirect.if.authenticated'])->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    });

    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });
});

// seller
Route::prefix('seller')->group(function () {
    Route::middleware(['guest', 'redirect.if.authenticated'])->group(function () {
        Route::get('/login', [SellerLoginController::class, 'showLoginForm'])->name('seller.login');
        Route::post('/login', [SellerLoginController::class, 'login'])->name('seller.login.submit');
    });

    Route::middleware(['seller.auth'])->group(function () {
        // Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    });
});

// Common logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');






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

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});








    // Users (RoleUserController)
    Route::get('/admin/users', [RoleUserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [RoleUserController::class, 'create'])->name('users.create');
    Route::post('/admin/users', [RoleUserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{id}/edit', [RoleUserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{id}', [RoleUserController::class, 'update'])->name('users.update');

    // IMPORTANT: your destroy() expects Request id, not {id} param
    Route::post('/admin/users/delete', [RoleUserController::class, 'destroy'])->name('users.destroy');




Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);

// role permission assign
Route::get('roles/{role}/permissions', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit');
Route::post('roles/{role}/permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update');

