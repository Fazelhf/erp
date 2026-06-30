<?php

declare(strict_types=1);

namespace Modules\HRM\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\HRM\Application\Commands\SubmitLeaveRequest\SubmitLeaveRequestCommand;
use Modules\HRM\Application\Queries\GetLeaveRequests\GetLeaveRequestsQuery;
use Modules\HRM\Domain\Leave\Enums\LeaveTypeEnum;
use Modules\HRM\Presentation\Http\Requests\SubmitLeaveRequestRequest;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class LeaveRequestController extends Controller
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): View
    {
        $requests = $this->queryBus->ask(new GetLeaveRequestsQuery(
            companyId: auth()->user()->company_id,
            userId:    auth()->id(),
        ));

        return view('hrm.leave.index', [
            'requests' => $requests,
            'types'    => LeaveTypeEnum::cases(),
        ]);
    }

    public function store(SubmitLeaveRequestRequest $request): RedirectResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new SubmitLeaveRequestCommand(
            companyId:  auth()->user()->company_id,
            userId:     auth()->id(),
            managerId:  $v['manager_id'],
            type:       $v['type'],
            startDate:  $v['start_date'],
            endDate:    $v['end_date'],
            reason:     $v['reason'],
        ));

        return back()->with('success', 'درخواست شما با موفقیت ثبت شد.');
    }
}
