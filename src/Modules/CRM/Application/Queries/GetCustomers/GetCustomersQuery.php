<?php

declare(strict_types=1);

namespace Modules\CRM\Application\Queries\GetCustomers;

final readonly class GetCustomersQuery
{
    public function __construct(
        public int     $companyId,
        public ?string $search  = null,
        public ?string $type    = null,
        public int     $perPage = 15,
    ) {}
}
