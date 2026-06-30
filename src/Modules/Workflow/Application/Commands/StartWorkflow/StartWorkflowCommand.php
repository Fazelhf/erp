<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Commands\StartWorkflow;

final readonly class StartWorkflowCommand
{
    public function __construct(
        public string $definitionSlug,
        public int    $companyId,
        public int    $initiatorId,
        public string $subjectType,
        public int    $subjectId,
        public array  $context = [],
    ) {}
}
