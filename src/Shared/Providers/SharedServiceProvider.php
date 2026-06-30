<?php

declare(strict_types=1);

namespace Shared\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\EventBusInterface;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Infrastructure\Bus\SynchronousCommandBus;
use Shared\Infrastructure\Bus\SynchronousEventBus;
use Shared\Infrastructure\Bus\SynchronousQueryBus;

class SharedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBusInterface::class, SynchronousCommandBus::class);
        $this->app->singleton(QueryBusInterface::class, SynchronousQueryBus::class);
        $this->app->singleton(EventBusInterface::class, SynchronousEventBus::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
