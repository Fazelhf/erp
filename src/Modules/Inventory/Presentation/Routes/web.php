<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Presentation\Http\Controllers\ProductController;

Route::middleware('auth')->prefix('inventory')->name('products.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('create');
    Route::post('/products', [ProductController::class, 'store'])->name('store');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('destroy');
});
