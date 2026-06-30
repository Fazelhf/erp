<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Presentation\Http\Controllers\DashboardController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
