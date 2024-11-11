<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\OrderAttachmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierDocumentController;
use App\Http\Controllers\UserController;
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

Route::resource('/category', CategoriesController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('/brands', BrandController::class)->only(['index', 'store', 'update', 'destroy']);
Route::patch('/products/update-price/{productId}/{newPrice}', [ProductController::class, 'updatePrice']);
Route::resource('/products', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
Route::patch('/supplier-document/trash/{id}', [SupplierDocumentController::class, 'moveToTrash']);
Route::patch('/supplier-document/recycle/{id}', [SupplierDocumentController::class, 'recycleDocument']);
Route::get('/supplier-document/show-documents/{supplierId}/{isActive}', [SupplierDocumentController::class, 'showDocuments']);
Route::resource('/supplier-document', SupplierDocumentController::class)->only(['store', 'destroy']);
Route::get('/order/show-order-by-status/{orderStatusId}/{supplierId}', [OrderController::class, 'showOrderByStatus']);
Route::get('/order/show-order-items/{referenceNumber}', [OrderController::class, 'showOrderItems']);
Route::patch('/order/update-order-status/{referenceNumber}/{newOrderStatus}', [OrderController::class, 'updateOrderStatus']);
Route::resource('/order-attachment', OrderAttachmentController::class)->only(['store']);

// Route::get('/auth/redirect', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/callback', function () {
//     $user = Socialite::driver('google')->user();
//     // $user->token
//     dd($user);
// });

Route::resource('/document-type', DocumentTypeController::class)->only(['index']);


Route::apiResource('/chats', ChatsController::class);
Route::apiResource('/messages', MessagesController::class);
Route::post('/messages/mark-read', [MessagesController::class, 'markAsRead']);

Route::get('/chat/users', [ChatsController::class, 'users']);
