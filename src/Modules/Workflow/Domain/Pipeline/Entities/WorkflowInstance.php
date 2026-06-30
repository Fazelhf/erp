<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Pipeline\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Workflow\Domain\Pipeline\Enums\WorkflowStatusEnum;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;

/**
 * A running instance of a WorkflowDefinition for a specific resource.
 * e.g., Leave Request #42 is at step "Manager Approval"
 */
class WorkflowInstance extends Model
{
    protected $fillable = [
        'workflow_definition_id',
        'company_id',
        'initiator_id',
        'subject_type',
        'subject_id',
        'current_step_id',
        'status',
        'context',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'status'       => WorkflowStatusEnum::class,
        'context'      => 'array',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function currentStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'current_step_id');
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class);
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, [WorkflowStatusEnum::Completed, WorkflowStatusEnum::Rejected]);
    }
}
