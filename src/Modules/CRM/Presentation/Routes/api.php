<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\CRM\Presentation\Http\Controllers\Api\V1\CustomerController;

Route::prefix('crm')->group(function () {
    Route::get('customers', [CustomerController::class, 'index'])
        ->middleware('permission:crm.customers.view');
    Route::post('customers', [CustomerController::class, 'store'])
        ->middleware('permission:crm.customers.create');
    Route::get('customers/{id}', [CustomerController::class, 'show'])
        ->middleware('permission:crm.customers.view');
    Route::put('customers/{id}', [CustomerController::class, 'update'])
        ->middleware('permission:crm.customers.edit');
    Route::delete('customers/{id}', [CustomerController::class, 'destroy'])
        ->middleware('permission:crm.customers.delete');
});
