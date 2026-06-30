<?php

declare(strict_types=1);

namespace App\Modules\IAM\Domain\Enums;

enum UserStatusEnum: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::Active    => 'فعال',
            self::Inactive  => 'غیرفعال',
            self::Suspended => 'تعلیق شده',
        };
    }
}
