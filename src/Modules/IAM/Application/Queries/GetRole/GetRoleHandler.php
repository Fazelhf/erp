<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetRole;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\IAM\Domain\Role\Entities\Role;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetRoleHandler implements QueryHandlerInterface
{
    public function handle(object $query): Role
    {
        /** @var GetRoleQuery $query */
        $role = Role::withoutGlobalScopes()->with('permissions')->find($query->roleId);

        if (! $role) {
            throw (new ModelNotFoundException())->setModel(Role::class, $query->roleId);
        }

        return $role;
    }
}
