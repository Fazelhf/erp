<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Presentation\Http\Controllers\Api\V1\ProductController;

Route::prefix('inventory')->group(function () {
    Route::get('products', [ProductController::class, 'index'])
        ->middleware('permission:inventory.products.view');
    Route::post('products', [ProductController::class, 'store'])
        ->middleware('permission:inventory.products.create');
    Route::get('products/{id}', [ProductController::class, 'show'])
        ->middleware('permission:inventory.products.view');
    Route::put('products/{id}', [ProductController::class, 'update'])
        ->middleware('permission:inventory.products.edit');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])
        ->middleware('permission:inventory.products.delete');
});
