<?php

declare(strict_types=1);

namespace Modules\Scheduler\Domain\Job\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Scheduler\Domain\Job\Enums\JobStatusEnum;

class ScheduledJob extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'handler',
        'cron_expression',
        'payload',
        'status',
        'last_run_at',
        'next_run_at',
        'last_error',
        'is_active',
    ];

    protected $casts = [
        'status'      => JobStatusEnum::class,
        'payload'     => 'array',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function isDue(): bool
    {
        return $this->is_active && $this->next_run_at?->isPast();
    }
}
