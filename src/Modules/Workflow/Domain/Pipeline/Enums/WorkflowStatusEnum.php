<?php

declare(strict_types=1);

namespace Modules\Workflow\Domain\Pipeline\Enums;

enum WorkflowStatusEnum: string
{
    case Pending   = 'pending';
    case Running   = 'running';
    case Completed = 'completed';
    case Rejected  = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'در انتظار',
            self::Running   => 'در جریان',
            self::Completed => 'تکمیل‌شده',
            self::Rejected  => 'رد شده',
            self::Cancelled => 'لغوشده',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending   => 'secondary',
            self::Running   => 'primary',
            self::Completed => 'success',
            self::Rejected  => 'danger',
            self::Cancelled => 'warning',
        };
    }
}
