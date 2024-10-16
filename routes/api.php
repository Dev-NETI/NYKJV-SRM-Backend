<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

//google routes
Route::get('/auth/redirect', [GoogleController::class, 'redirect']);
Route::get('/auth/callback', [GoogleController::class, 'callback']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/authenticating', [AuthController::class, 'authenticating']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
    Route::get('/checking-status-otp', [AuthController::class, 'checkingStatusOTP']);
    Route::middleware('otp_verified')->group(function () {
        Route::middleware('category_access')->group(function () {
            Route::resource('/category', CategoriesController::class)->only(['index', 'store', 'update']);
        });
        Route::middleware('brand_access')->group(function () {
            Route::resource('/brands', BrandController::class)->only(['index', 'store', 'update']);
        });
        Route::middleware('product_access')->group(function () {
            Route::resource('/products', ProductController::class)->only(['index', 'store', 'update']);
        });
        Route::middleware('document_access')->group(function () {
            Route::resource('/document-type', DocumentTypeController::class)->only(['index']);
        });
    });
});
