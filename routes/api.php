<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\DepartmentSupplierController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\OrderAttachmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDocumentController;
use App\Http\Controllers\OrderDocumentTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierDocumentController;
use App\Http\Controllers\UserController;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
            Route::resource('/category', CategoriesController::class)->only(['index', 'store', 'update', 'destroy']);
        });
        Route::middleware('brand_access')->group(function () {
            Route::resource('/brands', BrandController::class)->only(['index', 'store', 'update', 'destroy']);
        });
        Route::middleware('product_access')->group(function () {
            Route::resource('/products', ProductController::class)->only(['index', 'store', 'update', 'show', 'destroy']);
            Route::patch('/products/update-price/{productId}/{newPrice}', [ProductController::class, 'updatePrice']);
            Route::get('/products/total_count', [ProductController::class, 'total_count']);
            Route::patch('/products/update-price/{productId}/{newPrice}', [ProductController::class, 'updatePrice']);
            Route::get('/products/get-products/{supplierId}', [ProductController::class, 'getProduct']);
            Route::post('/products/update-product', [ProductController::class, 'patchProduct']);
        });
        Route::middleware('document_access')->group(function () {
            Route::resource('/document-type', DocumentTypeController::class)->only(['index']);
        });
    });
    Route::resource('/users-management', UserController::class)->only(['index', 'store', 'show', 'update']);

    Route::patch('/users-management/soft-delete/{slug}', [UserController::class, 'softDelete']);
    Route::resource('/department', DepartmentController::class)->only(['index']);
    Route::resource('/companies', CompanyController::class)->only(['index']);
    Route::resource('/supplier', SupplierController::class)->only(['store', 'index']);
    Route::get('/roles/available-roles/{id}', [RoleController::class, 'availableRoles']);
    Route::resource('/roles-user', RoleUserController::class)->only(['store', 'destroy']);
    Route::get('/roles-user/current-user-roles/{id}', [RoleUserController::class, 'currentUserRoles']);

    //Supplier Document
    Route::get('/company/{companyId}/department/{departmentId}/suppliers', [SupplierController::class, 'getSuppliersByCompanyAndDepartment']);
    Route::patch('/supplier-document/trash/{id}', [SupplierDocumentController::class, 'moveToTrash']);
    Route::patch('/supplier-document/recycle/{id}', [SupplierDocumentController::class, 'recycleDocument']);
    Route::get('/supplier-document/show-documents/{supplierId}/{isActive}', [SupplierDocumentController::class, 'showDocuments']);
    Route::resource('/supplier-document', SupplierDocumentController::class)->only(['store', 'destroy']);
    Route::post('/order/send-quotation', [OrderController::class, 'sendQuotation']);
    Route::get('/order/show-order-by-status/{orderStatusId}/{supplierId}', [OrderController::class, 'showOrderByStatus']);
    Route::get('/order/show-order-items/{referenceNumber}', [OrderController::class, 'showOrderItems']);
    Route::patch('/order/update-order-status/{referenceNumber}/{newOrderStatus}', [OrderController::class, 'updateOrderStatus']);
    Route::resource('/order-attachment', OrderAttachmentController::class)->only(['store']);
    Route::get('/supplier-document/show-documents-by-category/{supplierId}/{categoryId}/{isActive}', [SupplierDocumentController::class, 'showDocumentsByCategory']);
    Route::get('/supplier-document/missing-documents/{supplierId}/{categoryId}', [SupplierDocumentController::class, 'showMissingDocuments']);
    Route::get('/order-document/get-documents/{supplierId}/{departmentId}', [OrderDocumentController::class, 'showOrderDocument']);
    Route::resource('/order-document-type', OrderDocumentTypeController::class)->only(['index']);
    Route::get('/department-supplier/get-per-department/{departmentId}', [DepartmentSupplierController::class, 'showSupplierPerDepartment']);
    Route::get('/products/total_count', [ProductController::class, 'total_count']);

    Route::resource('/document-type', DocumentTypeController::class)->only(['index']);
    Route::apiResource('/chats', ChatsController::class);
    Route::apiResource('/messages', MessagesController::class);
    Route::post('/messages/mark-read', [MessagesController::class, 'markAsRead']);
    Route::get('/chat/users', [ChatsController::class, 'users']);
    Route::post('/chats/{chat}/participants', [ChatsController::class, 'addParticipant'])
        ->middleware('auth:sanctum');
    Route::delete('/chats/{chat}/participants/{user}', [ChatsController::class, 'removeParticipant'])
        ->middleware('auth:sanctum');

    Route::post('/users-management/update-profile', [UserController::class, 'updateProfile']);
});
