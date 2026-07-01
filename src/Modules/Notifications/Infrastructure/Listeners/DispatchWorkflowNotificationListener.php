<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Listeners;

use Modules\Notifications\Application\Jobs\SendWorkflowNotificationJob;
use Modules\Workflow\Domain\Pipeline\Events\WorkflowAdvancedEvent;

final class DispatchWorkflowNotificationListener
{
    public function handle(WorkflowAdvancedEvent $event): void
    {
        $instance   = $event->instance;
        $transition = $event->transition;

        // Notify the workflow initiator of any state change
        if ($instance->initiator_id) {
            SendWorkflowNotificationJob::dispatch(
                $instance->id,
                $transition->id,
                $instance->initiator_id,
            )->onQueue('notifications');
        }
    }
}
