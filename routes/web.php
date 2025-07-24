<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\ClientLoginController;

// ====================
// 🔐 CLIENT AUTH ROUTES
// ====================

// 📌 Client Signup (Multi-Step with OTP)
Route::get('/signup/step1', [ClientRegisterController::class, 'step1']);
Route::get('/signup/step2', fn() => Inertia::render('Client/Signup2'));
Route::post('/signup/step2', [ClientRegisterController::class, 'step2']);
Route::post('/signup/store', [ClientRegisterController::class, 'store']);
Route::get('/signup/verify-otp', fn() => Inertia::render('Client/VerifyOtp'));
Route::post('/signup/verify-otp', [ClientRegisterController::class, 'verifyOtp']);
Route::post('/signup/resend-otp', [ClientRegisterController::class, 'resendOtp']);

// 📌 Client Login / Logout
Route::get('/signin', [ClientLoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/signin', [ClientLoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [ClientLoginController::class, 'logout'])->name('logout');

// ===========================
// 🔐 PROTECTED CLIENT ROUTES
// ===========================

Route::middleware('auth:userclient')->group(function () {
    // Protected routes only accessible after login

    Route::get('/clientProfile', [ClientLoginController::class, 'profile'])->name('Profile');

    Route::get('/om-landing', function () {
        return Inertia::render('OnlineMarket/omLandingPage', [
            'user' => Auth::guard('userclient')->user(),
        ]);
    })->name('client.landing');
});

// ===========================
// CLIENT ROUTES
// ===========================


// ======================
// 🔧 OTHER ROUTES
// ======================
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
