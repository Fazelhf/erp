<?php

declare(strict_types=1);

namespace Modules\CRM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CRM\Application\Commands\CreateCustomer\CreateCustomerCommand;
use Modules\CRM\Application\Commands\CreateCustomer\CreateCustomerHandler;
use Modules\CRM\Application\Commands\UpdateCustomer\UpdateCustomerCommand;
use Modules\CRM\Application\Commands\UpdateCustomer\UpdateCustomerHandler;
use Modules\CRM\Application\Commands\DeleteCustomer\DeleteCustomerCommand;
use Modules\CRM\Application\Commands\DeleteCustomer\DeleteCustomerHandler;
use Modules\CRM\Application\Queries\GetCustomers\GetCustomersQuery;
use Modules\CRM\Application\Queries\GetCustomers\GetCustomersHandler;
use Modules\CRM\Application\Queries\GetCustomer\GetCustomerQuery;
use Modules\CRM\Application\Queries\GetCustomer\GetCustomerHandler;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class CRMServiceProvider extends ServiceProvider
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
        $bus->register(CreateCustomerCommand::class, CreateCustomerHandler::class);
        $bus->register(UpdateCustomerCommand::class, UpdateCustomerHandler::class);
        $bus->register(DeleteCustomerCommand::class, DeleteCustomerHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetCustomersQuery::class, GetCustomersHandler::class);
        $bus->register(GetCustomerQuery::class, GetCustomerHandler::class);
    }
}
