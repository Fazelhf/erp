<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\DeleteRole;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\IAM\Domain\Role\Entities\Role;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class DeleteRoleHandler implements CommandHandlerInterface
{
    public function handle(object $command): bool
    {
        /** @var DeleteRoleCommand $command */
        $role = Role::withoutGlobalScopes()->find($command->roleId);

        if (! $role) {
            throw (new ModelNotFoundException())->setModel(Role::class, $command->roleId);
        }

        return (bool) $role->delete();
    }
}
