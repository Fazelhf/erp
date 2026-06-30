<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Services;

use Illuminate\Support\Facades\DB;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowDefinition;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowStep;
use Modules\Workflow\Domain\Pipeline\Enums\WorkflowStatusEnum;
use Modules\Workflow\Domain\Pipeline\Events\WorkflowAdvancedEvent;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;
use Shared\Application\Bus\EventBusInterface;

final class WorkflowEngine
{
    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function start(
        string $definitionSlug,
        int    $companyId,
        int    $initiatorId,
        string $subjectType,
        int    $subjectId,
        array  $context = [],
    ): WorkflowInstance {
        $definition  = WorkflowDefinition::where('slug', $definitionSlug)
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->firstOrFail();

        $firstStep = $definition->steps()->orderBy('order')->first();

        return WorkflowInstance::create([
            'workflow_definition_id' => $definition->id,
            'company_id'             => $companyId,
            'initiator_id'           => $initiatorId,
            'subject_type'           => $subjectType,
            'subject_id'             => $subjectId,
            'current_step_id'        => $firstStep?->id,
            'status'                 => WorkflowStatusEnum::Running,
            'context'                => $context,
            'started_at'             => now(),
        ]);
    }

    public function advance(
        WorkflowInstance $instance,
        int              $actorId,
        string           $decision,
        ?string          $comment = null,
    ): WorkflowInstance {
        return DB::transaction(function () use ($instance, $actorId, $decision, $comment) {
            $currentStep = $instance->currentStep;

            $transition = WorkflowTransition::create([
                'workflow_instance_id' => $instance->id,
                'from_step_id'         => $currentStep?->id,
                'actor_id'             => $actorId,
                'decision'             => $decision,
                'comment'              => $comment,
            ]);

            if ($decision === 'reject') {
                $instance->update([
                    'status'       => WorkflowStatusEnum::Rejected,
                    'completed_at' => now(),
                ]);
            } else {
                $nextStep = $this->resolveNextStep($instance, $decision);

                if ($nextStep) {
                    $transition->update(['to_step_id' => $nextStep->id]);
                    $instance->update(['current_step_id' => $nextStep->id]);
                } else {
                    $instance->update([
                        'status'       => WorkflowStatusEnum::Completed,
                        'completed_at' => now(),
                    ]);
                }
            }

            $this->eventBus->publish(new WorkflowAdvancedEvent($instance->fresh(), $transition));

            return $instance->fresh();
        });
    }

    private function resolveNextStep(WorkflowInstance $instance, string $decision): ?WorkflowStep
    {
        $currentOrder = $instance->currentStep?->order ?? 0;

        return $instance->definition->steps()
            ->where('order', '>', $currentOrder)
            ->orderBy('order')
            ->first();
    }
}
