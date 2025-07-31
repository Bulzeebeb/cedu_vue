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
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ProductController;

// ======================
// MAIN LANDING PAGE ROUTES
// ======================

Route::get('/landingpage', function () {
    return Inertia::render('LandingPage');
})->name('landingpage');

Route::get('/onlinemarket', function () {
    return Inertia::render('onlinemarketmainpage');
})->name('onlinemarket');

Route::get('/paytopark', function () {
    return Inertia::render('paytoparkmainpage');
})->name('paytopark');

Route::get('/rentalfacility', function () {
    return Inertia::render('rentalfacilitymainpage');
})->name('rentalfacility');

Route::get('/adminchoice', function () {
    return Inertia::render('AdminChoice'); // Assuming you have an AdminChoice component
})->name('adminchoice');

Route::get('/clientchoice', function () {
    return Inertia::render('ClientChoice'); // Assuming you have an AdminChoice component
})->name('clientchoice');

/*
|--------------------------------------------------------------------------
| Admin Login Routes (Separated from Client)
|--------------------------------------------------------------------------
*/

Route::get('/signmain', [AuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin-login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/admin-logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Legacy Admin Auth Routes (Keep for compatibility but point to new routes)
|--------------------------------------------------------------------------
*/

Route::get('/login', function() {
    return redirect('/signmain');
})->name('login');

Route::post('/login', function() {
    return redirect('/admin-login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::put('/admins/products/{id}', [ProductController::class, 'update']);


/*
|--------------------------------------------------------------------------
| Admin Account Management (CRUD)
|--------------------------------------------------------------------------
*/

Route::prefix('admins')->group(function () {
    Route::get('/', [AdminAccountController::class, 'index']);
    Route::post('/', [AdminAccountController::class, 'store']);
    Route::put('/{admin}', [AdminAccountController::class, 'update']);
    Route::delete('/{admin}', [AdminAccountController::class, 'destroy']);
});

// ======================
// 🔐 SUPER ADMIN AUTH ROUTES
// ======================

Route::get('/superadmindashboard', function () {
    return Inertia::render('adminFinal/adminDashboard');
})->name('superadmindashboard');

Route::get('/superadminpay2parkupdate', function () {
    return Inertia::render('adminFinal/PtPUpdate');
})->name('superadminpay2parkupdate');

Route::get('/superadminonlinemarketupdate', function () {
    return Inertia::render('adminFinal/onlineMarketUpdate');
})->name('superadminonlinemarketupdate');

Route::get('/superadminupdatefacility', function () {
    return Inertia::render('adminFinal/updateFaci');
})->name('superadminupdatefacility');

Route::get('/superadminpay2parkreport', function () {
    return Inertia::render('adminFinal/PtPReport');
})->name('superadminpay2parkreport');

Route::get('/superadminonlinemarketreport', function () {
    return Inertia::render('adminFinal/onlineMarketReports');
})->name('superadminonlinemarketreport');

Route::get('/superadminfacilityreport', function () {
    return Inertia::render('adminFinal/reportFaci');
})->name('superadminfacilityreport');

Route::get('/superadminlogs', function () {
    return Inertia::render('adminFinal/adminLogs');
})->name('superadminlogs');

Route::get('/superadminprofile', function () {
    return Inertia::render('adminFinal/adminProfile');
})->name('superadminprofile');

Route::get('/superadminmanageaccount', function () {
    return Inertia::render('adminFinal/manageAccount');
})->name('superadminmanageaccount');

Route::get('/sidebar', function () {
    return Inertia::render('adminFinal/sidebar');
})->name('sidebar');


//KAYY JP NGA LOGIN
// 📌 SuperAdmin Login / Logout
//Route::get('/signin', [ClientLoginController::class, 'showLoginForm'])->name('login.form');
//Route::post('/signin', [ClientLoginController::class, 'login'])->name('login.attempt');
//Route::post('/logout', [ClientLoginController::class, 'logout'])->name('logout');

// ======================
// 🔐 ONLINE MARKET ADMIN ROUTES
// ======================
Route::get('/sidebarOnlineMarketAdmin', function () {
    return Inertia::render('OnlineMarket_ADMIN/adminSidebar');
})->name('sidebarOnlineMarketAdmin');

Route::get('/dashboardOnlineMarketAdmin', function () {
    return Inertia::render('OnlineMarket_ADMIN/adminDashboard');
})->name('dashboardOnlineMarketAdmin');

Route::get('/inventoryOnlineMarketAdmin', function () {
    return Inertia::render('OnlineMarket_ADMIN/adminInventory');
})->name('inventoryOnlineMarketAdmin');

Route::get('/productsOnlineMarketAdmin', function () {
    return Inertia::render('OnlineMarket_ADMIN/adminProducts');
})->name('productsOnlineMarketAdmin');

Route::get('/profileOnlineMarketAdmin', function () {
    return Inertia::render('OnlineMarket_ADMIN/adminProfile');
})->name('profileOnlineMarketAdmin');

Route::get('/reportsOnlineMarketAdmin', function () {
    return Inertia::render('OnlineMarket_ADMIN/adminReports');
})->name('reportsOnlineMarketAdmin');



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

// Online Market Landing page
Route::get('/onlinemarketlandingpageclient', function () {
    return Inertia::render('OnlineMarket/omLandingPage');
})->name('onlinemarketlandingpageclient');

// Vegetable page
Route::get('/vegetable', function () {
    return Inertia::render('OnlineMarket/vegetablePage');
})->name('vegetable');

//  Fruit page
Route::get('/fruit', function () {
    return Inertia::render('OnlineMarket/fruitPage'); // Make sure this matches the actual Vue file/component name
})->name('fruit');

//  Poultry page
Route::get('/poultry', function () {
    return Inertia::render('OnlineMarket/poultryPage');
})->name('poultry');

//  Add To Cart Page
Route::get('/cart', function () {
    return Inertia::render('OnlineMarket/addtocartPage');
})->name('cart');

//  View Product Page (dynamic {id} param)
Route::get('/viewproduct/{id}', function ($id) {
    return Inertia::render('OnlineMarket/viewproductPage', [
        'id' => $id
    ]);
})->name('viewproduct');


//  Checkout confirmation page
Route::get('/checkout', function () {
    return Inertia::render('OnlineMarket/checkoutPage');
})->name('checkout');


// ======================
// 🔐 LOGIN/LOGOUT CLIENT AUTH ROUTES
// ======================

Route::get('/home1', function () {
    return Inertia::render('Client/Login');
})->name('home1');

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

Route::get('/adminSidebarPayToPark', function () {
    return Inertia::render('PayToPark/adminSidebarP2P');
})->name('adminSidebarPayToPark');

Route::get('/adminAccountPayToPark', function () {
    return Inertia::render('PayToPark/admin_Account');
})->name('adminAccountPayToPark');

Route::get('/adminDashboardPayToPark', function () {
    return Inertia::render('PayToPark/admin_Dashboard');
})->name('adminDashboardPayToPark');

Route::get('/adminManageParkingPayToPark', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
})->name('adminManageParkingPayToPark');

Route::get('/reportsPayToPark', function () {
    return Inertia::render('PayToPark/admin_parking_reports');
})->name('reportsPayToPark');

Route::get('/billingPayToPark', function () {
    return Inertia::render('PayToPark/billing_checkout');
})->name('billingPayToPark');

Route::get('/editformPayToPark', function () {
    return Inertia::render('PayToPark/edit_Form');
})->name('editformPayToPark');

Route::get('/edithistoryPayToPark', function () {
    return Inertia::render('PayToPark/edit_History');
})->name('edithistoryPayToPark');

Route::get('/generatePOSPayToPark', function () {
    return Inertia::render('PayToPark/generate_pos');
})->name('generatePOSPayToPark');


Route::get('/onlinemarketPOS', function () {
    return Inertia::render('PayToPark/onlineMart_pos');
})->name('onlinemarketPOS');


Route::get('/generateQRPayToPark', function () {
    return Inertia::render('PayToPark/generate_QRcode');
})->name('generateQRPayToPark');

Route::get('/parkinghistoryPayToPark', function () {
    return Inertia::render('PayToPark/parking_History');
})->name('parkinghistoryPayToPark');

Route::get('/ci', function () {
    return Inertia::render('PayToPark/client_info');
})->name('ci');

// PaytoPark Payment 
Route::get('/payment_procedure', function () {
    return Inertia::render('PayToPark/payment_procedure');
})->name('payment_procedure');


// PaytoPark Payment 
Route::get('/cf', function () {
    return Inertia::render('PayToPark/client_Form');
})->name('cf');


// ======================
// 🔧 PAY2PARK STAFF ROUTES
// ======================
Route::get('/staffdashboardPayToPark', function () {
    return Inertia::render('PayToPark/staff_Dashboard');
})->name('staffdashboardPayToPark');


// ======================
// 🔧 PAY2PARK CLIENT ROUTES
// ======================
Route::get('/paytoparklandingpage', function () {
    return Inertia::render('PayToPark/client_Dashboard');
});


// ======================
// 🔧 RENTAL FACILITY CLIENT ROUTES
// ======================
Route::get('/rentallandingpage', function () {
    return Inertia::render('UseFaci/uf_home');
});

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Client Registration & OTP
|--------------------------------------------------------------------------
*/

Route::get('/signup/step1', [ClientRegisterController::class, 'step1']);
Route::get('/signup/step2', fn () => Inertia::render('Client/Signup2'));
Route::post('/signup/step2', [ClientRegisterController::class, 'step2']);
Route::post('/signup/store', [ClientRegisterController::class, 'store']);
Route::get('/signup/verify-otp', fn () => Inertia::render('Client/VerifyOtp'));
Route::post('/signup/verify-otp', [ClientRegisterController::class, 'verifyOtp']);
Route::post('/signup/resend-otp', [ClientRegisterController::class, 'resendOtp']);

Route::get('/signin', [ClientLoginController::class, 'showLoginForm'])->name('client.login.form');
Route::post('/signin', [ClientLoginController::class, 'login'])->name('client.login');
Route::post('/client-logout', [ClientLoginController::class, 'logout'])->name('client.logout');
Route::get('/client.landing', fn () => Inertia::render('ClientChoice'))->name('client.landing');

Route::middleware('auth:userclient')->group(function () {
    Route::get('/clientProfile', [ClientLoginController::class, 'profile'])->name('Profile');

    Route::get('/clientchoice', fn () => Inertia::render('ClientChoice', [
        'user' => Auth::guard('userclient')->user(),
    ]))->name('client.landing');

    Route::get('/clientSetting', fn () => Inertia::render('Client/clientSetting', [
        'client' => tap(Auth::guard('userclient')->user(), function ($client) {
            $client->image_url = $client->image_path
                ? asset('storage/' . $client->image_path)
                : '/images/default-profile.png';
        }),
    ]));

    Route::post('/client/setting', [ClientProfileController::class, 'update'])->name('client.profile.update');
    Route::get('/change-password', [ClientProfileController::class, 'showChangePasswordForm']);
    Route::post('/change-password', [ClientProfileController::class, 'updatePassword']);
    Route::post('/send-password-otp', [ClientProfileController::class, 'sendPasswordOtp']);
});

//ADMIN PRODUCTS
Route::post('/admins/products', [ProductController::class, 'store']);

Route::get('/admins/products', [ProductController::class, 'index']);
Route::post('/admins/products', [ProductController::class, 'store']);
Route::put('/admins/products/{product}', [ProductController::class, 'update']);
Route::delete('/admins/products/{id}', [ProductController::class, 'destroy']);
Route::post('/admins/products/bulk-delete', [ProductController::class, 'bulkDelete']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/api.php';