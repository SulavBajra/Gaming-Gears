<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\ProductHomeController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('api.login');
Route::post('/register', RegisterController::class)->name('api.register');
Route::get('/home', [ProductHomeController::class, 'index'])->name('api.home');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('api.shop');
Route::get('/shop/category/{category:slug}', [ShopController::class, 'similar']);
Route::post('/cart', [CartController::class, 'addToCart']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'role:customer']);

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    Route::post('/logout', LogoutController::class)->name('api.logout');
    Route::post('/cart', [CartController::class, 'store'])->name('api.cart');
    Route::get('/cart', [CartController::class, 'index']);
});
