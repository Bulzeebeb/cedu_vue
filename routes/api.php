<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use App\Http\Controllers\BookingController;


Route::apiResource('products', ProductController::class);
Route::apiResource('facilities', \App\Http\Controllers\FacilityController::class);
Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
Route::get('categories/active', [\App\Http\Controllers\CategoryController::class, 'getActive']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'Login successful',
        'token' => $token,
        'user' => [
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role
        ]
    ]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Logged out']);
})->middleware('auth:sanctum');