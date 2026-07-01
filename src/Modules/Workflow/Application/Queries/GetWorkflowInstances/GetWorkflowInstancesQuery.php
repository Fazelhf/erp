<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Queries\GetWorkflowInstances;

final readonly class GetWorkflowInstancesQuery
{
    public function __construct(
        public int     $companyId,
        public ?string $status  = null,
        public int     $perPage = 15,
    ) {}
}
