<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Commands\AdvanceWorkflow;

final readonly class AdvanceWorkflowCommand
{
    public function __construct(
        public int     $instanceId,
        public int     $actorId,
        public string  $decision,
        public ?string $comment = null,
    ) {}
}
