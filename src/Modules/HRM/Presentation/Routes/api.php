<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\HRM\Presentation\Http\Controllers\Api\V1\LeaveRequestController;

Route::prefix('hrm')->group(function () {
    Route::get('leave-requests', [LeaveRequestController::class, 'index']);
    Route::post('leave-requests', [LeaveRequestController::class, 'store']);
    Route::post('leave-requests/{id}/review', [LeaveRequestController::class, 'review']);
});
