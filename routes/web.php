<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClientRegisterController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\BuyHistoryController;
use App\Http\Controllers\OnlineMarketInventoryController;
use App\Http\Controllers\OnlineMarketOrdersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminUserClientController;
use App\Models\UserClient;
use App\Http\Controllers\AdminReportsController;
use App\Http\Controllers\LogController;
use App\Models\Log;

use App\Http\Controllers\PayPark\PayParkAdminReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PayPark\PayParkClientController;
use App\Http\Controllers\PayPark\PayParkTransactionController;
use App\Http\Controllers\PayPark\PayParkHistoryController;


Route::post('/admin/account/store', [AdminAccountController::class, 'store']);

// ======================
// MAIN LANDING PAGE ROUTES
// ======================

Route::get('/sample', function () {
    return Inertia::render('sample');
})->name('sample');

Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('landingpage');

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

Route::get('/aboutus', function () {
    return Inertia::render('AboutUs');
})->name('aboutus');

use App\Models\Admin;
Route::get('/adminchoice', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('AdminChoice', [
        'admin' => $admin
    ]);
})->name('adminchoice');

Route::get('/clientchoice', function () {
    return Inertia::render('ClientChoice');
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

Route::get('/login', function () {
    return redirect('/signmain');
})->name('login');

Route::post('/login', function () {
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
    Route::put('/{id}', [AdminAccountController::class, 'update']);
    Route::delete('/{id}', [AdminAccountController::class, 'destroy']);
    Route::put('/{id}/reset-password', [AdminAccountController::class, 'resetPassword']);
    Route::put('/{id}/status', [AdminAccountController::class, 'updateStatus']);
});



// Admin Account Management
Route::get('/admin/account/list', [AdminAccountController::class, 'list']);
Route::get('/admin/accounts', [AdminAccountController::class, 'fetch']);
Route::get('/admin/profile', [AdminAccountController::class, 'profile']);
Route::post('/admin/profile/update', [AdminAccountController::class, 'updateProfile']);

// Activity Logs Routes
Route::prefix('admin')->group(function () {
    Route::get('/activity-logs', [AdminAccountController::class, 'getActivityLogs']);
    Route::get('/activity-logs/stats', [AdminAccountController::class, 'getActivityLogStats']);
    Route::get('/activity-logs/admin/{adminId}', [AdminAccountController::class, 'getAdminActivityLogs']);
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
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminSidebar', [
        'admin' => $admin
    ]);
})->name('sidebarOnlineMarketAdmin');

// Updated dashboard route to use DashboardController
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboardOnlineMarketAdmin');

Route::prefix('admin')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('admin.orders.mark-paid');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
});

Route::get('/admins/inventory', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminInventory', [
        'admin' => $admin
    ]);
})->name('admins.inventory');

Route::get('/admins/products', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminProducts', [
        'admin' => $admin
    ]);
})->name('admins.products');

Route::get('/admins/manageacc', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminManageAccounts', [
        'admin' => $admin
    ]);
})->name('admins.manageacc');

Route::get('/admins/profile', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminMainProfile', [
        'admin' => $admin
    ]);
})->name('adminsprofile');

// Updated dashboard route to use AdminReportsController
Route::get('/admin/reports', [AdminReportsController::class, 'index'])->name('reportsOnlineMarketAdmin');

Route::get('/logsOnlineMarketAdmin', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminLogs', [
        'admin' => $admin
    ]);
})->name('logsOnlineMarketAdmin');

Route::get('/admins/orders', function () {
    $admin = Auth::guard('admin')->user();
    return Inertia::render('OnlineMarket_ADMIN/adminOrders', [
        'admin' => $admin
    ]);
})->name('admins.orders');

Route::prefix('admins/products')->group(function () {
    Route::get('/', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    Route::post('/', [ProductController::class, 'store'])->name('admin.products.store');
    Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('admin.products.bulk-delete');
    Route::put('/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

Route::prefix('admins/inventory')->group(function () {
    Route::get('/', [OnlineMarketInventoryController::class, 'adminIndex'])->name('admin.inventory.index');
    Route::post('/', [OnlineMarketInventoryController::class, 'store'])->name('admin.inventory.store');
    Route::post('/bulk-delete', [OnlineMarketInventoryController::class, 'bulkDelete'])->name('admin.inventory.bulk-delete');
    Route::put('/{id}', [OnlineMarketInventoryController::class, 'update'])->name('admin.inventory.update');
    Route::delete('/{id}', [OnlineMarketInventoryController::class, 'destroy'])->name('admin.inventory.destroy');
});

Route::prefix('admins/orders')->group(function () {
    Route::get('/', [OnlineMarketOrdersController::class, 'adminIndex'])->name('admin.orders.index');
    Route::post('/', [OnlineMarketOrdersController::class, 'store'])->name('admin.orders.store');
    Route::post('/bulk-delete', [OnlineMarketOrdersController::class, 'bulkDelete'])->name('admin.orders.bulk-delete');
    Route::put('/{id}', [OnlineMarketOrdersController::class, 'update'])->name('admin.orders.update');
    Route::delete('/{id}', [OnlineMarketOrdersController::class, 'destroy'])->name('admin.orders.destroy');
});


// Actual dashboard data
Route::get('/admin/dashboard/data', [DashboardController::class, 'index'])->name('admin.dashboard.data');

Route::prefix('admin')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}/mark-paid', [OrderController::class, 'markAsPaid'])->name('admin.orders.mark-paid');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/userclients', [AdminUserClientController::class, 'index']);
    Route::delete('/userclients/{id}', [AdminUserClientController::class, 'destroy']);
});



// ======================
// ONLINE MARKET ROUTES CLIENT
// ======================


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


// Product Routes
Route::get('/fruit', [ProductController::class, 'showFruits'])->name('fruits.index');
Route::get('/poultry', [ProductController::class, 'showPoultry'])->name('poultry.index');
Route::get('/vegetable', [ProductController::class, 'showVegetables'])->name('vegetables.index');










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



Route::get('/logs', [AuditLogController::class, 'index'])
    ->name('admin.audit.logs');


Route::post('/paytopark/clients', [PayParkClientController::class, 'store']);
// ======================
// 🔧 PAY2PARK ADMIN ROUTES
// ======================
// ✅ Landing page
// ✅ Staff Dashboard
Route::get('/pay_park_client/{id}', [PayParkClientController::class, 'show']);
Route::get('/paypark_transactions/{id}', [PayParkTransactionController::class, 'show']);

//newly added sa p2p
use App\Http\Controllers\PayPark\ParkingSettingsController;
Route::post('/parking-settings', [ParkingSettingsController::class, 'store'])->name('parking-settings.store');

Route::get('/reports', [PayParkAdminReportController::class, 'index'])
    ->name('admin.reports');

Route::get('/managep2p', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
})->name('managep2p');

// Main dashboard route


Route::get('/staffdashboardPayToPark', [PayParkClientController::class, 'index'])
    ->name('staffdashboardPayToPark');


Route::get('/paypark_transactions/today-with-clients', [PayParkTransactionController::class, 'paidTodayWithClients']);



Route::post('/paytopark/transactions', [PayParkTransactionController::class, 'store']);

use App\Http\Controllers\PayPark\PayParkDashboardController;

Route::get('/dashboard', [PayParkDashboardController::class, 'index'])->name('dashboard');



// Fetch all clients
Route::get('/clients', [PayParkClientController::class, 'index'])->name('clients.index');


// Fetch individual client info
Route::get('/clients/{id}', [PayParkClientController::class, 'show'])->name('clients.show');


// Update client
Route::put('/clients/{id}', [PayParkClientController::class, 'update'])->name('clients.update');



// Store new client (usually via modal form)
Route::post('/client', [PayParkClientController::class, 'store'])->name('client.store');


// Update existing client (for editing or marking as completed)
Route::put('/client/{id}', [PayParkClientController::class, 'update'])->name('client.update');


// Delete a client
Route::delete('/client/{id}', [PayParkClientController::class, 'destroy'])->name('client.destroy');


// Optional if you have a form-based editor
Route::get('/edit_Form/{id}', function ($id) {
    return Inertia::render('PayToPark/EditForm', ['clientId' => $id]);
})->name('client.editForm');


// Optional route if you have a separate page for parking history


Route::get('/parking_History', [PayParkHistoryController::class, 'index'])->name('parking.history');


use Illuminate\Support\Facades\DB;


// ======================
// 🔧 PAY2PARK STAFF ROUTES
// ======================



use Carbon\Carbon;

Route::get('/dashboard-stats', function () {
    $today = Carbon::today();

    return [
        'totalVehiclesParkedToday' => DB::table('pay_park_clients')
            ->whereDate('time_in', $today)
            ->count(),

        'vehiclesStillIn' => DB::table('pay_park_clients')
            ->whereNull('time_out')
            ->count(),

        'vehiclesOutToday' => DB::table('pay_park_clients')
            ->whereDate('time_out', $today)
            ->count(),

        'totalSalesToday' => DB::table('paypark_transactions')
            ->whereDate('transaction_date', $today)
            ->sum('total_payment'),
    ];
});



// ======================
// 🔧 PAY2PARK CLIENT ROUTES
// ======================
Route::get('/paytoparklandingpage', function () {
    return Inertia::render('PayToPark/client_Dashboard');
});

Route::post('/paytopark/scan', [PayParkTransactionController::class, 'scan']);

Route::get('/paypark_transactions/today-with-clients', [PayParkTransactionController::class, 'todayWithClients']);





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
Route::get('/signup/step2', fn() => Inertia::render('Client/Signup2'));
Route::post('/signup/step2', [ClientRegisterController::class, 'step2']);
Route::post('/signup/store', [ClientRegisterController::class, 'store']);
Route::get('/signup/verify-otp', fn() => Inertia::render('Client/VerifyOtp'));
Route::post('/signup/verify-otp', [ClientRegisterController::class, 'verifyOtp']);
Route::post('/signup/resend-otp', [ClientRegisterController::class, 'resendOtp']);

Route::get('/signin', [ClientLoginController::class, 'showLoginForm'])->name('client.login.form');
Route::post('/signin', [ClientLoginController::class, 'login'])->name('client.login');
Route::post('/client-logout', [ClientLoginController::class, 'logout'])->name('client.logout');
Route::get('/client.landing', fn() => Inertia::render('ClientChoice'))->name('client.landing');


Route::get('/pos', [CheckoutController::class, 'displayPOS'])->name('pos.display');

Route::get('/userclients', function () {
    return UserClient::all();
});

//Route::get('/userclients', [ClientLoginController::class, 'profile'])->name('client.profile');

// Routes that require client login
//Route::middleware('auth:userclient')->group(function () {

    Route::get('/om-landing', fn() => Inertia::render('ClientChoice', [
        'user' => Auth::guard('userclient')->user(),
    ]))->name('client.landing');

    Route::get('/clientSetting', fn() => Inertia::render('Client/clientSetting', [
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

    // Product Routes
    Route::get('/fruits', [ProductController::class, 'showFruits'])->name('fruits.index');
    Route::get('/poultry', [ProductController::class, 'showPoultry'])->name('poultry.index');
    Route::get('/vegetables', [ProductController::class, 'showVegetables'])->name('vegetables.index');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Buy History Routes
    Route::get('/buy-history', [BuyHistoryController::class, 'index'])->name('buy.history');
    Route::post('/orders/{orderId}/cancel', [BuyHistoryController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/cart/reorder', [BuyHistoryController::class, 'reorder'])->name('cart.reorder');
    Route::get('/orders/{orderId}', [BuyHistoryController::class, 'show'])->name('orders.show');
    Route::get('/api/order-stats', [BuyHistoryController::class, 'getOrderStats'])->name('api.order.stats');

    // Order History (Optional - for future implementation)
    // Route::get('/orders', [CheckoutController::class, 'orderHistory'])->name('orders.history');
    // Route::get('/orders/{order}', [CheckoutController::class, 'showOrder'])->name('orders.show');


// Logs
Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs');
Route::get('/api/logs', fn() => Log::orderByDesc('created_at')->get());

Route::put('/admins/{id}/status', [AdminAccountController::class, 'updateStatus']);
Route::get('/admins', [AdminAccountController::class, 'index'])->name('admins.index');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/api.php';