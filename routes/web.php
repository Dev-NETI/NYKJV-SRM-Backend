<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::post('/authenticating', [AuthController::class, 'authenticating']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::get('/checking-status-otp', [AuthController::class, 'checkingStatusOTP']);

Route::get('/auth/redirect', [GoogleController::class, 'redirect']);
Route::get('/auth/callback', [GoogleController::class, 'callback']);
// Route::get('/auth/redirect', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('google')->user();
//     // $user->token
//     dd($user);
// });

require __DIR__ . '/auth.php';

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
