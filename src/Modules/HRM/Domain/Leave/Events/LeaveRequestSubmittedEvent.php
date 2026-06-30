<?php

declare(strict_types=1);

namespace Modules\HRM\Domain\Leave\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\HRM\Domain\Leave\Entities\LeaveRequest;

final class LeaveRequestSubmittedEvent
{
    use Dispatchable;

    public function __construct(public readonly LeaveRequest $leaveRequest) {}
}
