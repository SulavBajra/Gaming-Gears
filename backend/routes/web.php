<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CustomerOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('users', UserController::class)->missing(function () {
        return Redirect::route('users.index');
    });
    Route::get('customers', [CustomerOrderController::class, 'index'])->name('customers.index');
    Route::get('customers/{order}/', [CustomerOrderController::class, 'edit'])->name('customers.edit');
    Route::patch('customers/{order}/', [CustomerOrderController::class, 'update'])->name('customers.update')
        ->missing(fn () => Redirect::route('customers.index'));
});

require __DIR__.'/settings.php';
