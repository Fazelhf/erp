<?php

declare(strict_types=1);

namespace App\Modules\HRM\Domain\Models;

use App\Modules\HRM\Domain\Enums\LeaveStatusEnum;
use App\Modules\HRM\Domain\Enums\LeaveTypeEnum;
use App\Modules\IAM\Domain\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    protected $table = 'hr_requests';

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'manager_id',
    ];

    protected function casts(): array
    {
        return [
            'type'       => LeaveTypeEnum::class,
            'status'     => LeaveStatusEnum::class,
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function isPending(): bool
    {
        return $this->status === LeaveStatusEnum::Pending;
    }
}
