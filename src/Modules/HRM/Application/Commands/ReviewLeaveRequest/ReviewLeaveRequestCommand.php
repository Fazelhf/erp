<?php

declare(strict_types=1);

namespace Modules\HRM\Application\Commands\ReviewLeaveRequest;

final readonly class ReviewLeaveRequestCommand
{
    public function __construct(
        public int    $leaveRequestId,
        public int    $reviewerId,
        public string $decision,
        public ?string $note = null,
    ) {}
}
