<?php

declare(strict_types=1);

namespace Modules\Workflow\Application\Services;

use Illuminate\Support\Facades\DB;
use Modules\IAM\Domain\User\Entities\User;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowDefinition;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowStep;
use Modules\Workflow\Domain\Pipeline\Enums\WorkflowStatusEnum;
use Modules\Workflow\Domain\Pipeline\Events\WorkflowAdvancedEvent;
use Modules\Workflow\Domain\Step\Enums\StepTypeEnum;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;
use Shared\Application\Bus\EventBusInterface;

final class WorkflowEngine
{
    private const MAX_AUTO_ADVANCE = 10;

    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function start(
        string $definitionSlug,
        int    $companyId,
        int    $initiatorId,
        string $subjectType,
        int    $subjectId,
        array  $context = [],
    ): WorkflowInstance {
        $definition = WorkflowDefinition::withoutGlobalScopes()
            ->where('slug', $definitionSlug)
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->firstOrFail();

        $firstStep = $definition->steps()->orderBy('order')->first();

        if (! $firstStep) {
            throw new \DomainException("Workflow definition '{$definitionSlug}' has no steps and cannot be started.");
        }

        return WorkflowInstance::create([
            'workflow_definition_id' => $definition->id,
            'company_id'             => $companyId,
            'initiator_id'           => $initiatorId,
            'subject_type'           => $subjectType,
            'subject_id'             => $subjectId,
            'current_step_id'        => $firstStep->id,
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
        if ($instance->isCompleted()) {
            throw new \DomainException('Cannot advance a completed or rejected workflow instance.');
        }

        $this->assertActorAuthorized($instance, $actorId);

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
            } elseif ($decision === 'delegate') {
                // Record the delegation; the step remains the same for the next approver
            } else {
                $instance = $instance->fresh(['currentStep', 'definition.steps']);
                $nextStep = $this->resolveNextStep($instance, $decision);

                if ($nextStep) {
                    $transition->update(['to_step_id' => $nextStep->id]);
                    $instance->update(['current_step_id' => $nextStep->id]);
                    $instance = $instance->fresh(['currentStep', 'definition.steps']);

                    $instance = $this->autoAdvanceNonApprovalSteps($instance, $actorId);
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

    private function assertActorAuthorized(WorkflowInstance $instance, int $actorId): void
    {
        $step = $instance->currentStep;

        if (! $step) {
            return;
        }

        if ($step->assignee_user_id !== null && $step->assignee_user_id !== $actorId) {
            throw new \DomainException('You are not the assigned approver for this step.');
        }

        if ($step->assignee_role !== null && $step->assignee_user_id === null) {
            $hasRole = User::withoutGlobalScopes()
                ->find($actorId)
                ?->roles()
                ->where('user_role.company_id', $instance->company_id)
                ->where(function ($q): void {
                    $q->whereNull('user_role.expires_at')
                      ->orWhere('user_role.expires_at', '>', now());
                })
                ->where('roles.slug', $step->assignee_role)
                ->exists();

            if (! $hasRole) {
                throw new \DomainException(
                    "Actor does not have the required role '{$step->assignee_role}' for this step."
                );
            }
        }
    }

    private function resolveNextStep(WorkflowInstance $instance, string $decision): ?WorkflowStep
    {
        $currentOrder = $instance->currentStep?->order ?? 0;
        $context      = $instance->context ?? [];

        $candidates = $instance->definition->steps()
            ->where('order', '>', $currentOrder)
            ->orderBy('order')
            ->get();

        foreach ($candidates as $step) {
            if (! empty($step->conditions) && ! $this->evaluateConditions($step->conditions, $context)) {
                if (! $step->is_required) {
                    continue;
                }
            }

            return $step;
        }

        return null;
    }

    private function autoAdvanceNonApprovalSteps(WorkflowInstance $instance, int $actorId): WorkflowInstance
    {
        $iterations = 0;

        while ($iterations++ < self::MAX_AUTO_ADVANCE) {
            $step = $instance->currentStep;

            if (! $step || $step->type === StepTypeEnum::Approval) {
                break;
            }

            $nextStep = $this->resolveNextStep($instance, 'approve');

            $transition = WorkflowTransition::create([
                'workflow_instance_id' => $instance->id,
                'from_step_id'         => $step->id,
                'to_step_id'           => $nextStep?->id,
                'actor_id'             => $actorId,
                'decision'             => 'auto',
                'comment'              => "Auto-advanced {$step->type->value} step",
            ]);

            if ($nextStep) {
                $instance->update(['current_step_id' => $nextStep->id]);
                $instance = $instance->fresh(['currentStep', 'definition.steps']);
            } else {
                $instance->update([
                    'status'       => WorkflowStatusEnum::Completed,
                    'completed_at' => now(),
                ]);
                break;
            }
        }

        return $instance;
    }

    /**
     * Evaluates a set of conditions against a context array.
     * Each condition: {field, operator, value}
     * All conditions must pass (AND logic).
     */
    private function evaluateConditions(array $conditions, array $context): bool
    {
        foreach ($conditions as $condition) {
            if (! isset($condition['field'], $condition['operator'], $condition['value'])) {
                continue;
            }

            $actual   = data_get($context, $condition['field']);
            $expected = $condition['value'];

            $passes = match ($condition['operator']) {
                'eq'      => $actual == $expected,
                'neq'     => $actual != $expected,
                'gt'      => $actual > $expected,
                'gte'     => $actual >= $expected,
                'lt'      => $actual < $expected,
                'lte'     => $actual <= $expected,
                'in'      => in_array($actual, (array) $expected),
                'not_in'  => ! in_array($actual, (array) $expected),
                'contains' => str_contains((string) $actual, (string) $expected),
                default   => true,
            };

            if (! $passes) {
                return false;
            }
        }

        return true;
    }
}
