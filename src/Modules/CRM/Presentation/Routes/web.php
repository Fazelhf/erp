<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\CRM\Presentation\Http\Controllers\CustomerController;

Route::middleware('auth')->prefix('crm')->name('customers.')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('store');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::patch('/customers/{id}', [CustomerController::class, 'update'])->name('update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('destroy');
});
