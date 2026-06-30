<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Kept minimal. Business logic and pagination belong in CoreServiceProvider.
 * Module registration happens via bootstrap/providers.php.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void {}
}
