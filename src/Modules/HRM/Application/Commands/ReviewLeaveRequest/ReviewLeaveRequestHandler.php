<?php

declare(strict_types=1);

namespace Modules\HRM\Application\Commands\ReviewLeaveRequest;

use Modules\HRM\Domain\Leave\Entities\LeaveRequest;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class ReviewLeaveRequestHandler implements CommandHandlerInterface
{
    public function handle(object $command): LeaveRequest
    {
        /** @var ReviewLeaveRequestCommand $command */
        $request = LeaveRequest::findOrFail($command->leaveRequestId);

        abort_if($request->manager_id !== $command->reviewerId, 403);

        $request->update([
            'status'       => $command->decision,
            'manager_note' => $command->note,
            'reviewed_at'  => now(),
        ]);

        return $request->fresh();
    }
}
