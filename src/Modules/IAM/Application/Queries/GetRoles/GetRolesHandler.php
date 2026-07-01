<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetRoles;

use Illuminate\Database\Eloquent\Collection;
use Modules\IAM\Domain\Role\Entities\Role;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetRolesHandler implements QueryHandlerInterface
{
    public function handle(object $query): Collection
    {
        /** @var GetRolesQuery $query */
        return Role::withoutGlobalScopes()
            ->where('company_id', $query->companyId)
            ->with('permissions')
            ->get();
    }
}
