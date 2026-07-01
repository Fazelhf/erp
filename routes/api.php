<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — /api/v1/
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api/v1/ automatically by bootstrap/app.php.
| Public routes (auth.login) are outside the auth:sanctum middleware.
| All other routes require a Sanctum bearer token.
|
*/

// ── Public ──────────────────────────────────────────────────────────────────
Route::middleware('throttle:60,1')->group(function () {
    require __DIR__ . '/../src/Modules/Auth/Presentation/Routes/api.php';
});

// ── Protected ───────────────────────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'throttle:api', 'company', 'locale', 'audit'])
    ->group(function () {
        require __DIR__ . '/../src/Modules/IAM/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Organization/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/CRM/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Inventory/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Accounting/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/HRM/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Workflow/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Reporting/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Settings/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Audit/Presentation/Routes/api.php';
        require __DIR__ . '/../src/Modules/Search/Presentation/Routes/api.php';
    });
