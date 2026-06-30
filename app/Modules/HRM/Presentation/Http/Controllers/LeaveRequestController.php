<?php

declare(strict_types=1);

namespace App\Modules\HRM\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HRM\Application\Actions\SubmitLeaveRequestAction;
use App\Modules\HRM\Application\DTOs\LeaveRequestData;
use App\Modules\HRM\Infrastructure\Repositories\LeaveRequestRepository;
use App\Modules\HRM\Presentation\Http\Requests\SubmitLeaveRequestRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class LeaveRequestController extends Controller
{
    public function __construct(
        private readonly LeaveRequestRepository  $repository,
        private readonly SubmitLeaveRequestAction $submitAction,
    ) {}

    public function index(Request $request): View
    {
        $hrRequests = $this->repository->forUser($request->user()->id);

        return view('hr.index', compact('hrRequests'));
    }

    public function store(SubmitLeaveRequestRequest $request): RedirectResponse
    {
        $this->submitAction->execute(LeaveRequestData::fromArray($request->validated()));

        return back()->with('success', 'درخواست شما با موفقیت ثبت شد.');
    }
}
