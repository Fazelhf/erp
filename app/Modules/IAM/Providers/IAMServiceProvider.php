<?php

declare(strict_types=1);

namespace App\Modules\IAM\Providers;

use Illuminate\Support\ServiceProvider;

final class IAMServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/web.php');
    }
}
