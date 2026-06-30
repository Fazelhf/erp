<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Pipeline\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Workflow\Domain\Step\Enums\StepTypeEnum;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_definition_id',
        'name',
        'type',
        'order',
        'assignee_role',
        'assignee_user_id',
        'conditions',
        'config',
        'is_required',
    ];

    protected $casts = [
        'type'       => StepTypeEnum::class,
        'conditions' => 'array',
        'config'     => 'array',
        'is_required' => 'boolean',
    ];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }
}
