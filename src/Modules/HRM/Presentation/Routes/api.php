<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\HRM\Presentation\Http\Controllers\Api\V1\LeaveRequestController;

Route::prefix('hrm')->group(function () {
    Route::get('leave-requests', [LeaveRequestController::class, 'index'])
        ->middleware('permission:hrm.leave.view_all');
    Route::post('leave-requests', [LeaveRequestController::class, 'store'])
        ->middleware('permission:hrm.leave.request');
    Route::post('leave-requests/{id}/review', [LeaveRequestController::class, 'review'])
        ->middleware('permission:hrm.leave.approve');
});
