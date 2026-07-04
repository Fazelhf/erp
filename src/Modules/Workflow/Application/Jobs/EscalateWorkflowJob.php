<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Audit\Domain\AuditLog\Entities\AuditLog;
use Modules\IAM\Domain\User\Entities\User;
use Modules\Notifications\Application\Jobs\SendWorkflowNotificationJob;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Pipeline\Enums\WorkflowStatusEnum;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;

final class EscalateWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    private const STALE_HOURS = 48;

    public function handle(): void
    {
        WorkflowInstance::withoutGlobalScopes()
            ->with(['currentStep'])
            ->where('status', WorkflowStatusEnum::Running)
            ->where('updated_at', '<', now()->subHours(self::STALE_HOURS))
            ->whereRaw("JSON_EXTRACT(context, '$.escalated_at') IS NULL")
            ->each(function (WorkflowInstance $instance): void {
                $this->escalate($instance);
            });
    }

    private function escalate(WorkflowInstance $instance): void
    {
        $context = $instance->context ?? [];
        $context['escalated_at'] = now()->toIso8601String();

        $instance->update([
            'context'    => $context,
            'updated_at' => now(),
        ]);

        AuditLog::create([
            'company_id'     => $instance->company_id,
            'user_id'        => null,
            'action'         => 'updated',
            'auditable_type' => 'workflow_instance',
            'auditable_id'   => $instance->id,
            'old_values'     => ['escalated' => false],
            'new_values'     => ['escalated' => true, 'stale_hours' => self::STALE_HOURS],
            'ip_address'     => null,
            'user_agent'     => 'scheduler',
        ]);

        $notifyUserId = $this->resolveNotifyUserId($instance);

        if (! $notifyUserId) {
            return;
        }

        $transition = WorkflowTransition::create([
            'workflow_instance_id' => $instance->id,
            'from_step_id'         => $instance->current_step_id,
            'to_step_id'           => $instance->current_step_id,
            'actor_id'             => $notifyUserId,
            'decision'             => 'escalated',
            'comment'              => "Auto-escalated after " . self::STALE_HOURS . " hours of inactivity.",
        ]);

        SendWorkflowNotificationJob::dispatch($instance->id, $transition->id, $notifyUserId);
    }

    private function resolveNotifyUserId(WorkflowInstance $instance): ?int
    {
        $step = $instance->currentStep;

        if (! $step) {
            return null;
        }

        if ($step->assignee_user_id) {
            return $step->assignee_user_id;
        }

        if ($step->assignee_role) {
            return User::withoutGlobalScopes()
                ->whereHas('roles', fn ($q) => $q
                    ->where('roles.slug', $step->assignee_role)
                    ->where('user_role.company_id', $instance->company_id)
                    ->where(function ($q): void {
                        $q->whereNull('user_role.expires_at')
                          ->orWhere('user_role.expires_at', '>', now());
                    })
                )
                ->where('company_id', $instance->company_id)
                ->value('id');
        }

        return $instance->initiator_id;
    }
}
