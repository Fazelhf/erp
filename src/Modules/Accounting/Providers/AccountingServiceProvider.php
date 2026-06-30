<?php

declare(strict_types=1);

namespace Modules\Accounting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Accounting\Application\Commands\CreateInvoice\CreateInvoiceCommand;
use Modules\Accounting\Application\Commands\CreateInvoice\CreateInvoiceHandler;
use Modules\Accounting\Application\Commands\DeleteInvoice\DeleteInvoiceCommand;
use Modules\Accounting\Application\Commands\DeleteInvoice\DeleteInvoiceHandler;
use Modules\Accounting\Application\Commands\MarkInvoicePaid\MarkInvoicePaidCommand;
use Modules\Accounting\Application\Commands\MarkInvoicePaid\MarkInvoicePaidHandler;
use Modules\Accounting\Application\Queries\GetInvoice\GetInvoiceQuery;
use Modules\Accounting\Application\Queries\GetInvoice\GetInvoiceHandler;
use Modules\Accounting\Application\Queries\GetInvoices\GetInvoicesQuery;
use Modules\Accounting\Application\Queries\GetInvoices\GetInvoicesHandler;
use Modules\Accounting\Domain\Tax\Services\TaxCalculationService;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class AccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TaxCalculationService::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Presentation/Routes/web.php');
        $this->registerCommandHandlers();
        $this->registerQueryHandlers();
    }

    private function registerCommandHandlers(): void
    {
        $bus = $this->app->make(CommandBusInterface::class);
        $bus->register(CreateInvoiceCommand::class, CreateInvoiceHandler::class);
        $bus->register(DeleteInvoiceCommand::class, DeleteInvoiceHandler::class);
        $bus->register(MarkInvoicePaidCommand::class, MarkInvoicePaidHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetInvoicesQuery::class, GetInvoicesHandler::class);
        $bus->register(GetInvoiceQuery::class, GetInvoiceHandler::class);
    }
}
