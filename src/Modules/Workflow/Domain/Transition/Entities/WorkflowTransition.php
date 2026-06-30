<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Transition\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowStep;

class WorkflowTransition extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'workflow_instance_id',
        'from_step_id',
        'to_step_id',
        'actor_id',
        'decision',
        'comment',
    ];

    public function instance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class, 'workflow_instance_id');
    }

    public function fromStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'from_step_id');
    }

    public function toStep(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'to_step_id');
    }
}
