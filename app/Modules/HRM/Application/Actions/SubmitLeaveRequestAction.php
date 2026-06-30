<?php

declare(strict_types=1);

namespace App\Modules\HRM\Application\Actions;

use App\Modules\HRM\Application\DTOs\LeaveRequestData;
use App\Modules\HRM\Domain\Events\LeaveRequestSubmittedEvent;
use App\Modules\HRM\Domain\Models\LeaveRequest;
use App\Modules\HRM\Infrastructure\Repositories\LeaveRequestRepository;
use Illuminate\Support\Facades\Auth;

final class SubmitLeaveRequestAction
{
    public function __construct(private readonly LeaveRequestRepository $repository) {}

    public function execute(LeaveRequestData $data): LeaveRequest
    {
        $leaveRequest = $this->repository->create([
            'user_id'    => Auth::id(),
            'type'       => $data->type->value,
            'start_date' => $data->startDate,
            'end_date'   => $data->endDate,
            'reason'     => $data->reason,
            'manager_id' => $data->managerId,
            'status'     => 'pending',
        ]);

        LeaveRequestSubmittedEvent::dispatch($leaveRequest);

        return $leaveRequest;
    }
}
