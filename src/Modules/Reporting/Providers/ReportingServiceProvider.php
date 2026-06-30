<?php

declare(strict_types=1);

namespace Modules\Reporting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Reporting\Application\Commands\GenerateReport\GenerateReportCommand;
use Modules\Reporting\Application\Commands\GenerateReport\GenerateReportHandler;
use Modules\Reporting\Application\Queries\GetReport\GetReportQuery;
use Modules\Reporting\Application\Queries\GetReport\GetReportHandler;
use Modules\Reporting\Infrastructure\Exporters\ExcelExporter;
use Modules\Reporting\Infrastructure\Exporters\PdfExporter;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class ReportingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PdfExporter::class);
        $this->app->singleton(ExcelExporter::class);
    }

    public function boot(): void
    {
        $cmdBus = $this->app->make(CommandBusInterface::class);
        $cmdBus->register(GenerateReportCommand::class, GenerateReportHandler::class);

        $qryBus = $this->app->make(QueryBusInterface::class);
        $qryBus->register(GetReportQuery::class, GetReportHandler::class);
    }
}
