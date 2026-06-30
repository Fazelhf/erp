<?php

declare(strict_types=1);

use App\Modules\HRM\Presentation\Http\Controllers\LeaveRequestController;
use App\Modules\HRM\Presentation\Http\Controllers\LeaveReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/hr/my-requests', [LeaveRequestController::class, 'index'])->name('hr.index');
    Route::post('/hr/request', [LeaveRequestController::class, 'store'])->name('hr.store');

    Route::get('/hr/admin/requests', [LeaveReviewController::class, 'index'])->name('hr.admin.index');
    Route::post('/hr/admin/requests/{leaveRequest}', [LeaveReviewController::class, 'update'])->name('hr.admin.update');
});
