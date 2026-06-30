<?php

declare(strict_types=1);

namespace Modules\HRM\Domain\Leave\Enums;

enum LeaveStatusEnum: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'در انتظار',
            self::Approved => 'تایید شده',
            self::Rejected => 'رد شده',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending  => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }
}
