<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Organization\Presentation\Http\Controllers\Api\V1\BranchController;
use Modules\Organization\Presentation\Http\Controllers\Api\V1\CompanyController;
use Modules\Organization\Presentation\Http\Controllers\Api\V1\DepartmentController;

Route::prefix('organization')->group(function () {
    Route::get('company', [CompanyController::class, 'index']);
    Route::patch('company/{id}', [CompanyController::class, 'update']);

    Route::apiResource('branches', BranchController::class);
    Route::apiResource('departments', DepartmentController::class);
});
