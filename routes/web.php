<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Client/Login');
})->name('home');

Route::get('/use-of-facilities', function () {
    return Inertia::render('UseFaci/uf_home');
});

Route::get('/use-of-facilities/hostel', function () {
    return Inertia::render('UseFaci/Hostel');
})->name('usefacilities.hostel');

Route::get('/use-of-facilities/commercial', function () {
    return Inertia::render('UseFaci/Commercial'); 
})->name('usefacilities.commercial');

Route::get('/use-of-facilities/rental', function () {
    return Inertia::render('UseFaci/Rental'); 
})->name('usefacilities.rental');


// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
