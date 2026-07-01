<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Presentation\Http\Controllers\Api\V1\InvoiceController;
use Modules\Accounting\Presentation\Http\Controllers\Api\V1\PaymentController;

Route::prefix('accounting')->group(function () {
    Route::get('invoices', [InvoiceController::class, 'index'])
        ->middleware('permission:accounting.invoices.view');
    Route::post('invoices', [InvoiceController::class, 'store'])
        ->middleware('permission:accounting.invoices.create');
    Route::get('invoices/{id}', [InvoiceController::class, 'show'])
        ->middleware('permission:accounting.invoices.view');
    Route::delete('invoices/{id}', [InvoiceController::class, 'destroy'])
        ->middleware('permission:accounting.invoices.delete');
    Route::post('invoices/{id}/mark-paid', [InvoiceController::class, 'markPaid'])
        ->middleware('permission:accounting.invoices.mark_paid');
    Route::post('invoices/{id}/cancel', [InvoiceController::class, 'cancel'])
        ->middleware('permission:accounting.invoices.create');

    Route::get('payments', [PaymentController::class, 'index'])
        ->middleware('permission:accounting.payments.view');
    Route::post('payments', [PaymentController::class, 'store'])
        ->middleware('permission:accounting.payments.create');
    Route::get('payments/{id}', [PaymentController::class, 'show'])
        ->middleware('permission:accounting.payments.view');
});
