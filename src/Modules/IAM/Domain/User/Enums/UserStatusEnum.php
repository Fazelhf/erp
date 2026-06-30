<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\User\Enums;

enum UserStatusEnum: string
{
    case Active    = 'active';
    case Inactive  = 'inactive';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::Active    => 'فعال',
            self::Inactive  => 'غیرفعال',
            self::Suspended => 'تعلیق‌شده',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active    => 'success',
            self::Inactive  => 'secondary',
            self::Suspended => 'danger',
        };
    }
}
