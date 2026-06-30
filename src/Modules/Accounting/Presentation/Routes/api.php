<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Presentation\Http\Controllers\Api\V1\InvoiceController;
use Modules\Accounting\Presentation\Http\Controllers\Api\V1\PaymentController;

Route::prefix('accounting')->group(function () {
    Route::apiResource('invoices', InvoiceController::class)->except(['update']);
    Route::post('invoices/{id}/mark-paid', [InvoiceController::class, 'markPaid']);

    Route::apiResource('payments', PaymentController::class)->only(['index', 'store', 'show']);
});
