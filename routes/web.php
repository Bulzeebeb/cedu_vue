<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('PayToPark/admin_Dashboard');
})->name('home');

Route::get('/admin_Account', function () {
    return Inertia::render('PayToPark/admin_Account');
});

Route::get('/admin_ManageParking', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
});

/*Route::get('/', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
})->name('home');

Route::get('/admin_ManageParking', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
});

Route::get('/admin_Vehicle', function () {
    return Inertia::render('PayToPark/admin_Vehicle');
});

Route::get('/admin_Dashborad', function () {
    return Inertia::render('PayToPark/admin_Dashboard');
});


Route::get('/', function () {
    return Inertia::render('PayToPark/admin_Vehicle');
})->name('home');

Route::get('/admin_ManageParking', function () {
    return Inertia::render('PayToPark/admin_ManageParking');
});

Route::get('/admin_Vehicle', function () {
    return Inertia::render('PayToPark/admin_Vehicle');
});

Route::get('/admin_Dashborad', function () {
    return Inertia::render('PayToPark/admin_Dashboard');
});

/*Route::get('/parking_History', function () {
    return Inertia::render('PayToPark/parking_History');
});

Route::get('/client_Form', function () {
    return Inertia::render('PayToPark/client_Form');
});

Route::get('/edit_Form', function () {
    return Inertia::render('PayToPark/edit_Form');
});
Route::get('/staff_Dashboard', function () {
    return Inertia::render('PayToPark/staff_Dashboard');
});

/*Route::get('/', function () {
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
})->name('usefacilities.rental');*/


// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
