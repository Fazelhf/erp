<?php

declare(strict_types=1);

namespace Modules\HRM\Domain\Leave\Events;

use Modules\HRM\Domain\Leave\Entities\LeaveRequest;

final class LeaveRequestSubmittedEvent
{
    public function __construct(public readonly LeaveRequest $leaveRequest) {}
}
