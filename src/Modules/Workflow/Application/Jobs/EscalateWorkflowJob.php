<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Audit\Domain\AuditLog\Entities\AuditLog;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Pipeline\Enums\WorkflowStatusEnum;

final class EscalateWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    /** Escalate running workflow instances stalled longer than this many hours */
    private const STALE_HOURS = 48;

    public function handle(): void
    {
        WorkflowInstance::where('status', WorkflowStatusEnum::Running)
            ->where('updated_at', '<', now()->subHours(self::STALE_HOURS))
            ->each(function (WorkflowInstance $instance): void {
                AuditLog::create([
                    'company_id'     => $instance->company_id,
                    'user_id'        => null,
                    'action'         => 'updated',
                    'auditable_type' => 'workflow_instance',
                    'auditable_id'   => $instance->id,
                    'old_values'     => ['status' => $instance->status?->value],
                    'new_values'     => ['escalated' => true, 'stale_hours' => self::STALE_HOURS],
                    'ip_address'     => null,
                    'user_agent'     => 'scheduler',
                ]);
            });
    }
}
