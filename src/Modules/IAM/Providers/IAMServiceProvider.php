<?php

declare(strict_types=1);

namespace Modules\IAM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\IAM\Application\Commands\CreateUser\CreateUserCommand;
use Modules\IAM\Application\Commands\CreateUser\CreateUserHandler;
use Modules\IAM\Application\Commands\UpdateProfile\UpdateProfileCommand;
use Modules\IAM\Application\Commands\UpdateProfile\UpdateProfileHandler;
use Modules\IAM\Application\Queries\GetUsers\GetUsersQuery;
use Modules\IAM\Application\Queries\GetUsers\GetUsersHandler;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class IAMServiceProvider extends ServiceProvider
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
        $bus->register(CreateUserCommand::class, CreateUserHandler::class);
        $bus->register(UpdateProfileCommand::class, UpdateProfileHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetUsersQuery::class, GetUsersHandler::class);
    }
}
