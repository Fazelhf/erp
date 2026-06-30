<?php

declare(strict_types=1);

namespace App\Modules\HRM\Application\Actions;

use App\Modules\HRM\Domain\Enums\LeaveStatusEnum;
use App\Modules\HRM\Domain\Models\LeaveRequest;
use App\Modules\HRM\Infrastructure\Repositories\LeaveRequestRepository;

final class ReviewLeaveRequestAction
{
    public function __construct(private readonly LeaveRequestRepository $repository) {}

    public function execute(LeaveRequest $leaveRequest, LeaveStatusEnum $status): LeaveRequest
    {
        return $this->repository->update($leaveRequest, [
            'status' => $status->value,
        ]);
    }
}
