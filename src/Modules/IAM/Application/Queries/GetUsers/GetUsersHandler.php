<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetUsers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\IAM\Domain\User\Entities\User;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetUsersHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetUsersQuery $query */
        return User::query()
            ->where('company_id', $query->companyId)
            ->when($query->search, fn ($q, $s) => $q->where(fn ($q) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
            ))
            ->latest()
            ->paginate($query->perPage);
    }
}
