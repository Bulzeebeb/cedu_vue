<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\ClientLoginController;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);


Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('lp');


// ======================
// MAIN LANDING PAGE ROUTES
// ======================
Route::get('/main', function () {
    return Inertia::render('LandingPage/mainpage');
})->name('main');

Route::get('/signmain', function () {
    return Inertia::render('LandingPage/signmain');
})->name('signmain');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = User::find(1);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Invalid credentials'
    ], 401);
});


// ======================
// 🔐 SUPER ADMIN AUTH ROUTES
// ======================
// 📌 SuperAdmin Login / Logout
Route::get('/signin', [ClientLoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/signin', [ClientLoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [ClientLoginController::class, 'logout'])->name('logout');


// ======================
// 🔐 ADMIN AUTH ROUTES
// ======================





// ======================
// ONLINE MARKET ROUTES CLIENT
// ======================

Route::get('/omd', function () {
    return Inertia::render('OnlineMarket/adminDashboard');
})->name('omd');

Route::get('/omu', function () {
    return Inertia::render('OnlineMarket/useoffac');
})->name('omu');

// Landing page
Route::get('/admin', function () {
    return Inertia::render('OnlineMarket/adminOnlineMarket');
})->name('admin');

// Landing page
Route::get('/lp', function () {
    return Inertia::render('LandingPage');
})->name('lp');

// Online Market Landing page
Route::get('/onlinemarketlandingpage', function () {
    return Inertia::render('OnlineMarket/omLandingPage');
})->name('onlinemarketlandingpage');

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


// ======================
// 🔐 LOGIN/LOGOUT CLIENT AUTH ROUTES
// ======================

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

// ======================
// 🔧 PAY2PARK ADMIN ROUTES
// ======================
// ✅ Landing page
Route::get('/adminAccount', function () {
    return Inertia::render('PayToPark/admin_Account');
})->name('adminAccount');

Route::get('/adminDashboard', function () {
    return Inertia::render('PayToPark/admin_Dashboard');
})->name('adminDashboard');

Route::get('/adminManageParking', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
})->name('adminManageParking');

Route::get('/p2preports', function () {
    return Inertia::render('PayToPark/admin_parking_reports');
})->name('reports');

// PaytoPark Payment 
Route::get('/payment_procedure', function () {
    return Inertia::render('PayToPark/payment_procedure');
});
// PaytoPark POS
Route::get('/generate_pos', function () {
    return Inertia::render('PayToPark/generate_pos');
});

// ======================
// 🔧 PAY2PARK CLIENT ROUTES
// ======================
Route::get('/paytoparklandingpage', function () {
    return Inertia::render('PayToPark/client_Dashboard');
});

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';