<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierUserController;
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

Route::resource('/category', CategoriesController::class)->only(['index', 'store','update', 'destroy']);
Route::resource('/brands', BrandController::class)->only(['index', 'store','update', 'destroy']);
Route::resource('/products', ProductController::class)->only(['index', 'store','update', 'destroy']);
Route::resource('/supplier', SupplierController::class)->only(['index', 'store', 'edit', 'destroy', 'show', 'update']);
Route::resource('/supplier-user', SupplierUserController::class)->only(['index', 'store', 'edit', 'destroy', 'show', 'update']);

Route::resource('/document-type',DocumentTypeController::class)->only(['index']);
