<?php

declare(strict_types=1);

namespace App\Modules\HRM\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\HRM\Application\Actions\ReviewLeaveRequestAction;
use App\Modules\HRM\Domain\Enums\LeaveStatusEnum;
use App\Modules\HRM\Domain\Models\LeaveRequest;
use App\Modules\HRM\Infrastructure\Repositories\LeaveRequestRepository;
use App\Modules\HRM\Presentation\Http\Requests\ReviewLeaveRequestRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class LeaveReviewController extends Controller
{
    public function __construct(
        private readonly LeaveRequestRepository $repository,
        private readonly ReviewLeaveRequestAction $reviewAction,
    ) {}

    public function index(): View
    {
        $hrRequests = $this->repository->allWithUsers();

        return view('hr.admin_index', compact('hrRequests'));
    }

    public function update(ReviewLeaveRequestRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $this->reviewAction->execute(
            $leaveRequest,
            LeaveStatusEnum::from($request->validated('status')),
        );

        return back()->with('success', 'وضعیت درخواست با موفقیت تغییر کرد.');
    }
}
