<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Commands\AdvanceWorkflow;

use Modules\Workflow\Application\Services\WorkflowEngine;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class AdvanceWorkflowHandler implements CommandHandlerInterface
{
    public function __construct(private readonly WorkflowEngine $engine) {}

    public function handle(object $command): WorkflowInstance
    {
        /** @var AdvanceWorkflowCommand $command */
        $instance = WorkflowInstance::with(['currentStep', 'definition.steps'])->findOrFail($command->instanceId);

        return $this->engine->advance(
            instance:  $instance,
            actorId:   $command->actorId,
            decision:  $command->decision,
            comment:   $command->comment,
        );
    }
}
