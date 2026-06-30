<?php

declare(strict_types=1);

namespace Modules\HRM\Application\Queries\GetLeaveRequests;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\HRM\Domain\Leave\Entities\LeaveRequest;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetLeaveRequestsHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetLeaveRequestsQuery $query */
        return LeaveRequest::query()
            ->with(['user', 'manager'])
            ->where('company_id', $query->companyId)
            ->when($query->userId,    fn ($q, $id) => $q->where('user_id', $id))
            ->when($query->managerId, fn ($q, $id) => $q->where('manager_id', $id))
            ->when($query->status,    fn ($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate($query->perPage);
    }
}
