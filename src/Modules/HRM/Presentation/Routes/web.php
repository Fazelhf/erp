<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\HRM\Presentation\Http\Controllers\LeaveRequestController;
use Modules\HRM\Presentation\Http\Controllers\LeaveReviewController;

Route::middleware('auth')->prefix('hrm')->group(function () {
    Route::get('/leave', [LeaveRequestController::class, 'index'])->name('leave.index');
    Route::post('/leave', [LeaveRequestController::class, 'store'])->name('leave.store');

    Route::get('/leave/review', [LeaveReviewController::class, 'index'])->name('leave.review');
    Route::patch('/leave/{id}/review', [LeaveReviewController::class, 'update'])->name('leave.review.update');
});
