<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Pipeline\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shared\Domain\Models\Concerns\HasCompanyScope;

/**
 * Blueprint: defines the steps and transitions for a given process.
 * Example: "Purchase Request Approval", "Leave Request Flow"
 */
class WorkflowDefinition extends Model
{
    use HasCompanyScope;
    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'applies_to',
        'is_active',
        'config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config'    => 'array',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class)->orderBy('order');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(WorkflowInstance::class);
    }
}
