<?php

declare(strict_types=1);

namespace Modules\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Dashboard\Application\Queries\GetDashboardStats\GetDashboardStatsQuery;
use Modules\Dashboard\Application\Queries\GetDashboardStats\GetDashboardStatsHandler;
use Shared\Application\Bus\QueryBusInterface;

class DashboardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Presentation/Routes/web.php');

        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetDashboardStatsQuery::class, GetDashboardStatsHandler::class);
    }
}
