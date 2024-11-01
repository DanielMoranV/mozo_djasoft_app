<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryMovementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomBroadcastController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;




// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'store'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    // Rutas que requieren autenticación
    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::post('/me', [AuthController::class, 'me'])->name('auth.me');
    });
});

// Parameters Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('parameters', ParameterController::class);
});

// Warehouses Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('warehouses', WarehouseController::class);
});

// Role Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'getRoles'])->name('roles.index');
    Route::post('/', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/user', [RoleController::class, 'assignRole'])->name('roles.assign');
    Route::delete('/user', [RoleController::class, 'removeRole'])->name('roles.remove');
});

// User Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::prefix('users')->group(function () {
        Route::post('/store', [UserController::class, 'storeUsers'])->name('users.storeMultiple');
        Route::patch('/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::post('/{id}/photoprofile', [UserController::class, 'photoProfile'])->name('users.photoProfile');
    });
});

// Company Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('companies', CompanyController::class);
    Route::post('companies/{id}/logo', [CompanyController::class, 'logo'])->name('companies.logo');
});

// Product Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::post('products/store', [ProductController::class, 'storeProducts'])->name('products.storeMultiple');
});

// Category Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::post('categories/store', [CategoryController::class, 'storeCategories'])->name('categories.storeMultiple');
});

// Unit Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('units', UnitController::class);
    Route::post('units/store', [UnitController::class, 'storeUnits'])->name('units.storeMultiple');
});

// CategoryMovement Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('category-movements', CategoryMovementController::class);
});

// StockMovements Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('stock-movements', StockMovementController::class);
    Route::post('stock-movements/store-entry', [StockMovementController::class, 'storeEntry'])->name('stock-movements.store-entry');
});

// Providers management routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('providers', ProviderController::class);
});

// Purchase Orders Management Routes
Route::middleware(['auth:api', 'role:dev|admin'])->group(function () {
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::get('purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'generatePdf'])->name('purchase-orders.generate-pdf');
    Route::post('purchase-orders/store-purchase-order-and-details', [PurchaseOrderController::class, 'storePurchaseOrderAndDetails'])->name('purchase-orders.store-purchase-order-and-details');
});

// Ruta para validar el auth de broadcasting
// Route::middleware(['auth:api'])->post('broadcasting/auth', function () {
//     return response()->json(['message' => 'Authenticated for broadcasting'], 200);
// })->name('broadcasting.auth')->middleware('auth:api')->fallback(function () {
//     return response()->json(['error' => 'Unauthorized'], 401);
// });

// Registrar la ruta de autorización de broadcasting
//Broadcast::routes(['middleware' => ['auth:api']]);