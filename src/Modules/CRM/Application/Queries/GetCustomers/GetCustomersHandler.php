<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Queries\GetCustomers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\CRM\Domain\Customer\Entities\Customer;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetCustomersHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetCustomersQuery $query */
        return Customer::query()
            ->where('company_id', $query->companyId)
            ->when($query->search, fn ($q, $s) => $q->where(fn ($q) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('national_id', 'like', "%{$s}%")
            ))
            ->when($query->type, fn ($q, $t) => $q->where('type', $t))
            ->latest()
            ->paginate($query->perPage);
    }
}
