<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\CreateRole;

use Modules\IAM\Domain\Role\Entities\Role;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateRoleHandler implements CommandHandlerInterface
{
    public function handle(object $command): Role
    {
        /** @var CreateRoleCommand $command */
        $role = Role::create([
            'company_id'  => $command->companyId,
            'name'        => $command->name,
            'slug'        => $command->slug,
            'description' => $command->description,
        ]);

        if (! empty($command->permissionIds)) {
            $role->permissions()->sync($command->permissionIds);
        }

        return $role->load('permissions');
    }
}
