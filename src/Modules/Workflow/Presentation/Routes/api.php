<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Workflow\Presentation\Http\Controllers\Api\V1\WorkflowController;

Route::prefix('workflow')->group(function () {
    Route::get('instances', [WorkflowController::class, 'index']);
    Route::post('instances', [WorkflowController::class, 'start']);
    Route::get('instances/{id}', [WorkflowController::class, 'show']);
    Route::post('instances/{id}/advance', [WorkflowController::class, 'advance']);
});
