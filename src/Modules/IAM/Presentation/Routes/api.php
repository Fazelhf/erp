<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\IAM\Presentation\Http\Controllers\Api\V1\RoleController;
use Modules\IAM\Presentation\Http\Controllers\Api\V1\UserController;

Route::prefix('iam')->group(function () {
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:iam.users.view');
    Route::get('users/{id}', [UserController::class, 'show'])
        ->middleware('permission:iam.users.view');

    Route::get('roles', [RoleController::class, 'index'])
        ->middleware('permission:iam.roles.view');
    Route::post('roles', [RoleController::class, 'store'])
        ->middleware('permission:iam.roles.manage');
    Route::get('roles/{id}', [RoleController::class, 'show'])
        ->middleware('permission:iam.roles.view');
    Route::put('roles/{id}', [RoleController::class, 'update'])
        ->middleware('permission:iam.roles.manage');
    Route::delete('roles/{id}', [RoleController::class, 'destroy'])
        ->middleware('permission:iam.roles.manage');
});
