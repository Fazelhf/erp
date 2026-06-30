<?php

declare(strict_types=1);

namespace Modules\HRM\Application\Queries\GetLeaveRequests;

final readonly class GetLeaveRequestsQuery
{
    public function __construct(
        public int   $companyId,
        public ?int  $userId    = null,
        public ?int  $managerId = null,
        public ?string $status  = null,
        public int   $perPage   = 15,
    ) {}
}
