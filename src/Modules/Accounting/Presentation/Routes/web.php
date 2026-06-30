<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Accounting\Presentation\Http\Controllers\InvoiceController;

Route::middleware('auth')->prefix('accounting')->name('invoices.')->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('show');
    Route::patch('/invoices/{id}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
});
