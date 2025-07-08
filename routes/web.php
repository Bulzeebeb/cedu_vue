<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
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


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
