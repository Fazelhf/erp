<?php

declare(strict_types=1);

namespace Modules\Organization\Application\Queries\GetCompanies;

final readonly class GetCompaniesQuery
{
    public function __construct(
        public int $tenantId,
        public int $perPage = 15,
    ) {}
}
