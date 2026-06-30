<?php

declare(strict_types=1);

namespace Modules\HRM\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\HRM\Application\Commands\ReviewLeaveRequest\ReviewLeaveRequestCommand;
use Modules\HRM\Application\Queries\GetLeaveRequests\GetLeaveRequestsQuery;
use Modules\HRM\Presentation\Http\Requests\ReviewLeaveRequestRequest;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class LeaveReviewController extends Controller
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(): View
    {
        $requests = $this->queryBus->ask(new GetLeaveRequestsQuery(
            companyId: auth()->user()->company_id,
            managerId: auth()->id(),
        ));

        return view('hrm.leave.review', compact('requests'));
    }

    public function update(ReviewLeaveRequestRequest $request, int $id): RedirectResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new ReviewLeaveRequestCommand(
            leaveRequestId: $id,
            reviewerId:     auth()->id(),
            decision:       $v['decision'],
            note:           $v['note'] ?? null,
        ));

        return back()->with('success', 'درخواست بررسی شد.');
    }
}
