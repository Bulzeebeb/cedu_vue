<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes for CEDU Online Market
|--------------------------------------------------------------------------
|
| Here is where you define the main pages for your online market.
| Each page returns an Inertia Vue component.
|
*/

// ✅ Landing page
Route::get('/', function () {
    return Inertia::render('OnlineMarket/omLandingPage');
})->name('home');

// ✅ Vegetable page
Route::get('/vegetable', function () {
    return Inertia::render('OnlineMarket/vegetablePage');
})->name('vegetable');

// ✅ Fruit page
Route::get('/fruit', function () {
    return Inertia::render('OnlineMarket/fruitPage');
})->name('fruit');

// ✅ Poultry page
Route::get('/poultry', function () {
    return Inertia::render('OnlineMarket/poultryPage');
})->name('poultry');

// ✅ 🛒 Add To Cart Page
Route::get('/cart', function () {
    return Inertia::render('OnlineMarket/addtocartPage');
})->name('cart');

// ✅ 🆕 View Product Page (dynamic {id} param)
Route::get('/viewproduct/{id}', function ($id) {
    return Inertia::render('OnlineMarket/viewproductPage', [
        'id' => $id
    ]);
})->name('viewproduct');


// ✅ Checkout confirmation page
Route::get('/checkout', function () {
    return Inertia::render('OnlineMarket/checkoutPage');
})->name('checkout');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
