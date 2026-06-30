<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Commands\StartWorkflow;

use Modules\Workflow\Application\Services\WorkflowEngine;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class StartWorkflowHandler implements CommandHandlerInterface
{
    public function __construct(private readonly WorkflowEngine $engine) {}

    public function handle(object $command): WorkflowInstance
    {
        /** @var StartWorkflowCommand $command */
        return $this->engine->start(
            definitionSlug: $command->definitionSlug,
            companyId:      $command->companyId,
            initiatorId:    $command->initiatorId,
            subjectType:    $command->subjectType,
            subjectId:      $command->subjectId,
            context:        $command->context,
        );
    }
}
