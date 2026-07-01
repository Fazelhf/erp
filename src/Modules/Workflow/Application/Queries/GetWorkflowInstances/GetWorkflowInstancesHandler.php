<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Queries\GetWorkflowInstances;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetWorkflowInstancesHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetWorkflowInstancesQuery $query */
        return WorkflowInstance::withoutGlobalScopes()
            ->with(['currentStep'])
            ->where('company_id', $query->companyId)
            ->when($query->status, fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate($query->perPage);
    }
}
