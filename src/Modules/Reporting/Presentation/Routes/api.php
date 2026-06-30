<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Presentation\Http\Controllers\Api\V1\ReportController;

Route::prefix('reporting')->group(function () {
    Route::apiResource('reports', ReportController::class)->except(['update']);
});
