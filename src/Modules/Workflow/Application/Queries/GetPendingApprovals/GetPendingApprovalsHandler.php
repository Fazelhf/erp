<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Queries\GetPendingApprovals;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Pipeline\Enums\WorkflowStatusEnum;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetPendingApprovalsHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetPendingApprovalsQuery $query */
        return WorkflowInstance::query()
            ->with(['definition', 'currentStep'])
            ->where('company_id', $query->companyId)
            ->where('status', WorkflowStatusEnum::Running)
            ->whereHas('currentStep', fn ($q) =>
                $q->where('assignee_user_id', $query->userId)
                  ->orWhereHas('definition.steps', fn ($q) =>
                      $q->where('assignee_role', function ($sub) use ($query) {
                          $sub->from('roles')
                              ->join('user_role', 'roles.id', '=', 'user_role.role_id')
                              ->where('user_role.user_id', $query->userId)
                              ->select('roles.slug');
                      })
                  )
            )
            ->latest()
            ->paginate($query->perPage);
    }
}
