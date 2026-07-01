<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Queries\GetWorkflowInstance;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetWorkflowInstanceHandler implements QueryHandlerInterface
{
    public function handle(object $query): WorkflowInstance
    {
        /** @var GetWorkflowInstanceQuery $query */
        $instance = WorkflowInstance::withoutGlobalScopes()
            ->with(['currentStep', 'transitions'])
            ->find($query->instanceId);

        if (! $instance) {
            throw (new ModelNotFoundException())->setModel(WorkflowInstance::class, $query->instanceId);
        }

        return $instance;
    }
}
