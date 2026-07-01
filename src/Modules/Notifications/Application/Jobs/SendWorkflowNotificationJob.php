<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\IAM\Domain\User\Entities\User;
use Modules\Notifications\Domain\Notification\WorkflowActionNotification;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;

final class SendWorkflowNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly int $instanceId,
        private readonly int $transitionId,
        private readonly int $notifyUserId,
    ) {}

    public function handle(): void
    {
        $instance   = WorkflowInstance::find($this->instanceId);
        $transition = WorkflowTransition::find($this->transitionId);
        $notifyUser = User::find($this->notifyUserId);

        if (! $instance || ! $transition || ! $notifyUser) {
            return;
        }

        $notifyUser->notify(new WorkflowActionNotification($instance, $transition));
    }
}
