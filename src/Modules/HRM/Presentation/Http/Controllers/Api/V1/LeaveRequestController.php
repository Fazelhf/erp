<?php

declare(strict_types=1);

namespace Modules\HRM\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\HRM\Application\Commands\ReviewLeaveRequest\ReviewLeaveRequestCommand;
use Modules\HRM\Application\Commands\SubmitLeaveRequest\SubmitLeaveRequestCommand;
use Modules\HRM\Application\Queries\GetLeaveRequests\GetLeaveRequestsQuery;
use Modules\HRM\Presentation\Http\Resources\LeaveRequestResource;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class LeaveRequestController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $requests = $this->queryBus->ask(new GetLeaveRequestsQuery(
            companyId: $request->user()->company_id,
            userId:    $request->integer('user_id') ?: null,
            status:    $request->input('status'),
        ));

        return $this->ok(LeaveRequestResource::collection($requests)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'       => ['required', 'string', 'in:annual,sick,maternity,paternity,unpaid'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['required', 'string'],
            'manager_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $this->commandBus->dispatch(new SubmitLeaveRequestCommand(
            companyId:  $request->user()->company_id,
            userId:     $request->user()->id,
            managerId:  $data['manager_id'],
            type:       $data['type'],
            startDate:  $data['start_date'],
            endDate:    $data['end_date'],
            reason:     $data['reason'],
        ));

        return $this->created(null, 'Leave request submitted');
    }

    public function review(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'decision' => ['required', 'string', 'in:approve,reject'],
            'note'     => ['nullable', 'string'],
        ]);

        $this->commandBus->dispatch(new ReviewLeaveRequestCommand(
            leaveRequestId: $id,
            reviewerId:     $request->user()->id,
            decision:       $data['decision'],
            note:           $data['note'] ?? null,
        ));

        return $this->ok(null, 'Leave request reviewed');
    }
}
