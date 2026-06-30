<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Queries\GetPendingApprovals;

final readonly class GetPendingApprovalsQuery
{
    public function __construct(
        public int $companyId,
        public int $userId,
        public int $perPage = 15,
    ) {}
}
