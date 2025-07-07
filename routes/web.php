<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('home');

Route::get('/ptpcheckout', function () {
    return Inertia::render('PayToPark/billing_checkout');
});

Route::get('/ptp-to-billing', function () {
    return Inertia::render('PayToPark/client_Dashboard');
});

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
