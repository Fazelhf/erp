<?php

declare(strict_types=1);

namespace Modules\Workflow\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Workflow\Application\Commands\AdvanceWorkflow\AdvanceWorkflowCommand;
use Modules\Workflow\Application\Commands\AdvanceWorkflow\AdvanceWorkflowHandler;
use Modules\Workflow\Application\Commands\StartWorkflow\StartWorkflowCommand;
use Modules\Workflow\Application\Commands\StartWorkflow\StartWorkflowHandler;
use Modules\Workflow\Application\Queries\GetPendingApprovals\GetPendingApprovalsQuery;
use Modules\Workflow\Application\Queries\GetPendingApprovals\GetPendingApprovalsHandler;
use Modules\Workflow\Application\Services\WorkflowEngine;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class WorkflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WorkflowEngine::class);
    }

    public function boot(): void
    {
        $cmdBus = $this->app->make(CommandBusInterface::class);
        $cmdBus->register(StartWorkflowCommand::class, StartWorkflowHandler::class);
        $cmdBus->register(AdvanceWorkflowCommand::class, AdvanceWorkflowHandler::class);

        $qryBus = $this->app->make(QueryBusInterface::class);
        $qryBus->register(GetPendingApprovalsQuery::class, GetPendingApprovalsHandler::class);
    }
}
