<?php

declare(strict_types=1);

namespace App\Modules\HRM\Providers;

use App\Modules\HRM\Infrastructure\Repositories\LeaveRequestRepository;
use Illuminate\Support\ServiceProvider;

final class HRMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LeaveRequestRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/web.php');
    }
}
