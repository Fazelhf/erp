<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Listeners;

use Modules\HRM\Domain\Leave\Events\LeaveRequestSubmittedEvent;
use Modules\Notifications\Application\Jobs\SendLeaveNotificationJob;

final class DispatchLeaveNotificationListener
{
    public function handle(LeaveRequestSubmittedEvent $event): void
    {
        $req = $event->leaveRequest;

        // Notify the manager when a leave is submitted
        if ($req->manager_id) {
            SendLeaveNotificationJob::dispatch($req->id, 'submitted', $req->manager_id)
                ->onQueue('notifications');
        }
    }
}
