<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\CRM\Presentation\Http\Controllers\Api\V1\CustomerController;

Route::prefix('crm')->group(function () {
    Route::apiResource('customers', CustomerController::class);
});
