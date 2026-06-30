<?php

declare(strict_types=1);

use App\Modules\Accounting\Presentation\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('invoices', InvoiceController::class);
});
