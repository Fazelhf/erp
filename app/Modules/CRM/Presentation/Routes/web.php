<?php

declare(strict_types=1);

use App\Modules\CRM\Presentation\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
});
