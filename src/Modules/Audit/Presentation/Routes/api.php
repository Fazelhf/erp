<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Audit\Presentation\Http\Controllers\Api\V1\AuditLogController;

Route::prefix('audit')->group(function () {
    Route::get('logs', [AuditLogController::class, 'index'])
        ->middleware('permission:audit.logs.view');
    Route::get('logs/{id}', [AuditLogController::class, 'show'])
        ->middleware('permission:audit.logs.view');
});
