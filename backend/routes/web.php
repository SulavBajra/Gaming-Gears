<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Dashboard', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
});

require __DIR__.'/settings.php';
