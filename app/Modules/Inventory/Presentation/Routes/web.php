<?php

declare(strict_types=1);

use App\Modules\Inventory\Presentation\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});
