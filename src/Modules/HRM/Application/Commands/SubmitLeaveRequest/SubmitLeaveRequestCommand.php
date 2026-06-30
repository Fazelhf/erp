<?php

declare(strict_types=1);

namespace Modules\HRM\Application\Commands\SubmitLeaveRequest;

final readonly class SubmitLeaveRequestCommand
{
    public function __construct(
        public int    $companyId,
        public int    $userId,
        public int    $managerId,
        public string $type,
        public string $startDate,
        public string $endDate,
        public string $reason,
    ) {}
}
