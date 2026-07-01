<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\UpdateRole;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\IAM\Domain\Role\Entities\Role;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class UpdateRoleHandler implements CommandHandlerInterface
{
    public function handle(object $command): Role
    {
        /** @var UpdateRoleCommand $command */
        $role = Role::withoutGlobalScopes()->find($command->roleId);

        if (! $role) {
            throw (new ModelNotFoundException())->setModel(Role::class, $command->roleId);
        }

        $role->update(array_filter([
            'name'        => $command->name,
            'description' => $command->description,
        ], fn ($v) => $v !== null));

        if ($command->permissionIds !== null) {
            $role->permissions()->sync($command->permissionIds);
        }

        return $role->load('permissions');
    }
}
