<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierDocumentController;
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

Route::resource('/category', CategoriesController::class)->only(['index', 'store','update']);
Route::resource('/brands', BrandController::class)->only(['index', 'store','update']);
Route::resource('/products', ProductController::class)->only(['index', 'store','update']);
Route::patch('/supplier-document/trash/{id}', [SupplierDocumentController::class, 'moveToTrash']);
Route::patch('/supplier-document/recycle/{id}', [SupplierDocumentController::class, 'recycleDocument']);
Route::get('/supplier-document/show-documents/{supplierId}/{isActive}', [SupplierDocumentController::class, 'showDocuments']);
Route::resource('/supplier-document', SupplierDocumentController::class)->only(['store','destroy']);

// Route::get('/auth/redirect', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('google')->user();
//     // $user->token
//     dd($user);
// });

Route::resource('/document-type',DocumentTypeController::class)->only(['index']);
