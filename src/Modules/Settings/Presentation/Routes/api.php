<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Settings\Presentation\Http\Controllers\Api\V1\SettingController;

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::put('/', [SettingController::class, 'update']);
});
