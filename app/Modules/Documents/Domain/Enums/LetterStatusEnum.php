<?php

declare(strict_types=1);

namespace App\Modules\Documents\Domain\Enums;

enum LetterStatusEnum: string
{
    case Pending  = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::Pending  => 'در انتظار',
            self::Approved => 'تأیید شده',
            self::Rejected => 'رد شده',
        };
    }
}
