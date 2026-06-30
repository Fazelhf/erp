<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Queries\GetInvoices;

final readonly class GetInvoicesQuery
{
    public function __construct(
        public int     $companyId,
        public ?string $search     = null,
        public ?string $status     = null,
        public ?int    $customerId = null,
        public int     $perPage    = 15,
    ) {}
}
