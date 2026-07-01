<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\HRM\Domain\Leave\Entities\LeaveRequest;
use Modules\IAM\Domain\User\Entities\User;
use Modules\Notifications\Domain\Notification\LeaveRequestNotification;

final class SendLeaveNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly int    $leaveRequestId,
        private readonly string $event,      // 'submitted' | 'approved' | 'rejected'
        private readonly int    $notifyUserId,
    ) {}

    public function handle(): void
    {
        $leaveRequest = LeaveRequest::find($this->leaveRequestId);
        $notifyUser   = User::find($this->notifyUserId);

        if (! $leaveRequest || ! $notifyUser) {
            return;
        }

        $notifyUser->notify(new LeaveRequestNotification($leaveRequest, $this->event));
    }
}
