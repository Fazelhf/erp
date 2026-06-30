<?php

declare(strict_types=1);

namespace Modules\Organization\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Organization\Application\Commands\CreateTenant\CreateTenantCommand;
use Modules\Organization\Application\Commands\CreateTenant\CreateTenantHandler;
use Modules\Organization\Application\Commands\CreateCompany\CreateCompanyCommand;
use Modules\Organization\Application\Commands\CreateCompany\CreateCompanyHandler;
use Modules\Organization\Application\Queries\GetCompanies\GetCompaniesQuery;
use Modules\Organization\Application\Queries\GetCompanies\GetCompaniesHandler;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class OrganizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerCommandHandlers();
        $this->registerQueryHandlers();
    }

    private function registerCommandHandlers(): void
    {
        /** @var \Shared\Infrastructure\Bus\SynchronousCommandBus $bus */
        $bus = $this->app->make(CommandBusInterface::class);
        $bus->register(CreateTenantCommand::class, CreateTenantHandler::class);
        $bus->register(CreateCompanyCommand::class, CreateCompanyHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        /** @var \Shared\Infrastructure\Bus\SynchronousQueryBus $bus */
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetCompaniesQuery::class, GetCompaniesHandler::class);
    }
}
