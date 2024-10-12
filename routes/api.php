<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/authenticating', [AuthController::class, 'authenticating']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::get('/checking-status-otp', [AuthController::class, 'checkingStatusOTP']);

Route::get('/auth/redirect', [GoogleController::class, 'redirect']);
Route::get('/auth/callback', [GoogleController::class, 'callback']);
Route::get('/dashboard', [SupplierController::class, 'index']);
Route::post('/dashboard/store', [SupplierController::class, 'store']);
Route::get('/dashboard/{id}/edit', [SupplierController::class, 'show']);
Route::put('/dashboard/{id}', [SupplierController::class, 'update']);


// Route::get('/auth/redirect', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('google')->user();
//     // $user->token
//     dd($user);
// });
