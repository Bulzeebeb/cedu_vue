<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('PayToPark/admin_parking_reports');
})->name('home');

Route::get('/payment_procedure', function () {
    return Inertia::render('PayToPark/payment_procedure');
});
Route::get('/generate_pos', function () {
});


// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
