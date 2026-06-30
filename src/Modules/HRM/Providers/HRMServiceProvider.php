<?php

declare(strict_types=1);

namespace Modules\HRM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\HRM\Application\Commands\SubmitLeaveRequest\SubmitLeaveRequestCommand;
use Modules\HRM\Application\Commands\SubmitLeaveRequest\SubmitLeaveRequestHandler;
use Modules\HRM\Application\Commands\ReviewLeaveRequest\ReviewLeaveRequestCommand;
use Modules\HRM\Application\Commands\ReviewLeaveRequest\ReviewLeaveRequestHandler;
use Modules\HRM\Application\Queries\GetLeaveRequests\GetLeaveRequestsQuery;
use Modules\HRM\Application\Queries\GetLeaveRequests\GetLeaveRequestsHandler;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class HRMServiceProvider extends ServiceProvider
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
        $bus->register(SubmitLeaveRequestCommand::class, SubmitLeaveRequestHandler::class);
        $bus->register(ReviewLeaveRequestCommand::class, ReviewLeaveRequestHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetLeaveRequestsQuery::class, GetLeaveRequestsHandler::class);
    }
}
