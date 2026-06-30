<?php

declare(strict_types=1);

namespace Modules\Search\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Search\Application\Queries\GlobalSearch\GlobalSearchQuery;
use Modules\Search\Application\Queries\GlobalSearch\GlobalSearchHandler;
use Modules\Search\Domain\Contracts\SearchDriverInterface;
use Modules\Search\Infrastructure\Drivers\DatabaseSearchDriver;
use Modules\Search\Infrastructure\Drivers\MeilisearchDriver;
use Shared\Application\Bus\QueryBusInterface;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SearchDriverInterface::class, function () {
            $host   = config('scout.meilisearch.host');
            $apiKey = config('scout.meilisearch.key');

            if ($host && $apiKey) {
                return new MeilisearchDriver($host, $apiKey);
            }

            return new DatabaseSearchDriver();
        });
    }

    public function boot(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GlobalSearchQuery::class, GlobalSearchHandler::class);
    }
}
