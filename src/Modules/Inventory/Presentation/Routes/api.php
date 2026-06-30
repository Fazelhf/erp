<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Presentation\Http\Controllers\Api\V1\ProductController;

Route::prefix('inventory')->group(function () {
    Route::apiResource('products', ProductController::class);
});
