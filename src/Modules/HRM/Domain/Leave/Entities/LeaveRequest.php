<?php

declare(strict_types=1);

namespace Modules\HRM\Domain\Leave\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\HRM\Domain\Leave\Enums\LeaveStatusEnum;
use Modules\HRM\Domain\Leave\Enums\LeaveTypeEnum;
use Modules\IAM\Domain\User\Entities\User;

class LeaveRequest extends Model
{
    protected $table = 'hr_requests';

    protected $fillable = [
        'company_id',
        'user_id',
        'manager_id',
        'type',
        'status',
        'start_date',
        'end_date',
        'reason',
        'manager_note',
        'reviewed_at',
    ];

    protected $casts = [
        'type'        => LeaveTypeEnum::class,
        'status'      => LeaveStatusEnum::class,
        'start_date'  => 'date',
        'end_date'    => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
