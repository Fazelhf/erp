<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Entry Route
|--------------------------------------------------------------------------
| All module routes are registered automatically by their respective
| ServiceProvider (see bootstrap/providers.php). This file only defines
| the root redirect.
*/

Route::get('/', fn () => redirect()->route('dashboard'));
