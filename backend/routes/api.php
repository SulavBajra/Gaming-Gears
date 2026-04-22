<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CustomerProfileController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductHomeController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class)->name('api.login');
Route::post('/register', RegisterController::class)->name('api.register');
Route::get('/home', [ProductHomeController::class, 'index'])->name('api.home');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('api.shop');
Route::get('/shop/category/{category:slug}', [ShopController::class, 'similar']);
Route::get('/shops/', [ShopController::class, 'index']);

Route::get('/brands', function () {
    $brands = Brand::with('media')->where('is_active', true)->select('id', 'name', 'slug')->get();

    return response()->json(BrandResource::collection($brands));
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'role:customer']);

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    // Change these for guest in future
    Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('api.shop');

    Route::post('/logout', LogoutController::class)->name('api.logout');
    Route::post('/cart', [CartController::class, 'store'])->name('api.cart');
    Route::get('/cart', [CartController::class, 'index']);
    Route::delete('/cart/{cart}', [CartController::class, 'destroy']);
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'deleteItem']);
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateItem']);
    Route::post('/checkout/intent', [CheckoutController::class, 'createPaymentIntent']);
    // Route::post('/checkout/cod', [CheckoutController::class, 'cashOnDelivery']);

    // this is for the user profile section
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('api.profile');
    Route::post('/profile', [CustomerProfileController::class, 'store']);
    Route::put('/profile', [CustomerProfileController::class, 'update']);
    Route::patch('/profile', [CustomerProfileController::class, 'update']);

    Route::get('/orders', OrderController::class);

    // TODO: Add for wishlist
    Route::get('/wishlist', [WishlistController::class, 'show']);
    Route::patch('/wishlist', [WishlistController::class, 'update']);
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy']);
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
