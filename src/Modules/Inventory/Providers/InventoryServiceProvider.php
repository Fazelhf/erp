<?php

declare(strict_types=1);

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Application\Commands\CreateProduct\CreateProductCommand;
use Modules\Inventory\Application\Commands\CreateProduct\CreateProductHandler;
use Modules\Inventory\Application\Commands\DeleteProduct\DeleteProductCommand;
use Modules\Inventory\Application\Commands\DeleteProduct\DeleteProductHandler;
use Modules\Inventory\Application\Queries\GetProducts\GetProductsQuery;
use Modules\Inventory\Application\Queries\GetProducts\GetProductsHandler;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class InventoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Presentation/Routes/web.php');
        $this->registerCommandHandlers();
        $this->registerQueryHandlers();
    }

    private function registerCommandHandlers(): void
    {
        $bus = $this->app->make(CommandBusInterface::class);
        $bus->register(CreateProductCommand::class, CreateProductHandler::class);
        $bus->register(DeleteProductCommand::class, DeleteProductHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetProductsQuery::class, GetProductsHandler::class);
    }
}
