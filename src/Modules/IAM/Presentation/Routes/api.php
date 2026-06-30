<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\IAM\Presentation\Http\Controllers\Api\V1\RoleController;
use Modules\IAM\Presentation\Http\Controllers\Api\V1\UserController;

Route::prefix('iam')->group(function () {
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('roles', RoleController::class);
});
