<?php

declare(strict_types=1);

namespace Modules\IAM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\IAM\Application\Commands\CreateRole\CreateRoleCommand;
use Modules\IAM\Infrastructure\Persistence\RbacGuard;
use Modules\IAM\Application\Commands\CreateRole\CreateRoleHandler;
use Modules\IAM\Application\Commands\CreateUser\CreateUserCommand;
use Modules\IAM\Application\Commands\CreateUser\CreateUserHandler;
use Modules\IAM\Application\Commands\DeleteRole\DeleteRoleCommand;
use Modules\IAM\Application\Commands\DeleteRole\DeleteRoleHandler;
use Modules\IAM\Application\Commands\UpdateProfile\UpdateProfileCommand;
use Modules\IAM\Application\Commands\UpdateProfile\UpdateProfileHandler;
use Modules\IAM\Application\Commands\UpdateRole\UpdateRoleCommand;
use Modules\IAM\Application\Commands\UpdateRole\UpdateRoleHandler;
use Modules\IAM\Application\Queries\GetRole\GetRoleQuery;
use Modules\IAM\Application\Queries\GetRole\GetRoleHandler;
use Modules\IAM\Application\Queries\GetRoles\GetRolesQuery;
use Modules\IAM\Application\Queries\GetRoles\GetRolesHandler;
use Modules\IAM\Application\Queries\GetUser\GetUserQuery;
use Modules\IAM\Application\Queries\GetUser\GetUserHandler;
use Modules\IAM\Application\Queries\GetUsers\GetUsersQuery;
use Modules\IAM\Application\Queries\GetUsers\GetUsersHandler;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class IAMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RbacGuard::class);
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
        $bus->register(CreateRoleCommand::class, CreateRoleHandler::class);
        $bus->register(CreateUserCommand::class, CreateUserHandler::class);
        $bus->register(DeleteRoleCommand::class, DeleteRoleHandler::class);
        $bus->register(UpdateProfileCommand::class, UpdateProfileHandler::class);
        $bus->register(UpdateRoleCommand::class, UpdateRoleHandler::class);
    }

    private function registerQueryHandlers(): void
    {
        $bus = $this->app->make(QueryBusInterface::class);
        $bus->register(GetRoleQuery::class, GetRoleHandler::class);
        $bus->register(GetRolesQuery::class, GetRolesHandler::class);
        $bus->register(GetUserQuery::class, GetUserHandler::class);
        $bus->register(GetUsersQuery::class, GetUsersHandler::class);
    }
}
