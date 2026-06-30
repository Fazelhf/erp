<?php

declare(strict_types=1);

namespace Modules\Organization\Application\Queries\GetCompanies;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Organization\Domain\Company\Entities\Company;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetCompaniesHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetCompaniesQuery $query */
        return Company::query()
            ->where('tenant_id', $query->tenantId)
            ->latest()
            ->paginate($query->perPage);
    }
}
