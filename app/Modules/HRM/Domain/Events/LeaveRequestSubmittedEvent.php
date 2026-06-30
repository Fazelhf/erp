<?php

declare(strict_types=1);

namespace App\Modules\HRM\Domain\Events;

use App\Modules\HRM\Domain\Models\LeaveRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class LeaveRequestSubmittedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly LeaveRequest $leaveRequest) {}
}
