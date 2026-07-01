<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Queries\GetWorkflowInstance;

final readonly class GetWorkflowInstanceQuery
{
    public function __construct(public int $instanceId) {}
}
