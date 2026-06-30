<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Pipeline\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;

final class WorkflowAdvancedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly WorkflowInstance   $instance,
        public readonly WorkflowTransition $transition,
    ) {}
}
