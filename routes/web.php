<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
<<<<<<< Updated upstream
    return Inertia::render('LandingPage');
=======
>>>>>>> Stashed changes
    return Inertia::render('PayToPark/edit_Form');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
