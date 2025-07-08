<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
<<<<<<< HEAD
    return Inertia::render('PayToPark/staff_Dashboard');
})->name('home');

Route::get('/client_Form', function () {
    return Inertia::render('PayToPark/client_Form');
})->name('client.form');

Route::get('/parking_History', function () {
    return Inertia::render('PayToPark/parking_History');
});

Route::get('/edit_Form', function () {
    return Inertia::render('PayToPark/edit_Form');
});

=======
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
>>>>>>> 2810bed1bc6edd03ab4c47b86dce0f827a560e45

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
