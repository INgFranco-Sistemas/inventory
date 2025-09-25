<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Config\SucursalController;
use App\Http\Controllers\Config\WarehouseController;
use App\Http\Controllers\Config\ProductCategorieController;
use App\Http\Controllers\Config\ProviderController;
use App\Http\Controllers\Config\UnitController;
use App\Http\Controllers\Config\UnitConversionController;

Route::group([
    // 'middleware' => 'api',
    'prefix' => 'auth',
    // 'middleware' => ['auth:api', 'role:admin'],
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group([
    "middleware" => ["auth:api"]
], function ($router) {
    Route::resource("role", RoleController::class);
    Route::get("users/config", [UserController::class, "config"]);
    Route::post("users/{id}", [UserController::class, "update"]);
    Route::resource("users", UserController::class);
    
    Route::resource("sucursales", SucursalController::class);
    Route::resource("warehouses", WarehouseController::class);

    Route::post("categories/{id}", [ProductCategorieController::class, "update"]);
    Route::resource("categories", ProductCategorieController::class);

    Route::post("providers/{id}", [ProviderController::class, "update"]);
    Route::resource("providers", ProviderController::class);

    Route::resource("units", UnitController::class);

    Route::resource("unit-conversions", UnitConversionController::class);
});
