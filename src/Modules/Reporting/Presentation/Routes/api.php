<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Presentation\Http\Controllers\Api\V1\ReportController;

Route::prefix('reporting')->group(function () {
    Route::get('reports', [ReportController::class, 'index'])
        ->middleware('permission:reporting.view');
    Route::post('reports', [ReportController::class, 'store'])
        ->middleware('permission:reporting.create');
    Route::get('reports/{id}', [ReportController::class, 'show'])
        ->middleware('permission:reporting.view');
    Route::delete('reports/{id}', [ReportController::class, 'destroy'])
        ->middleware('permission:reporting.create');
});
