<?php

declare(strict_types=1);

namespace Modules\HRM\Domain\Leave\Enums;

enum LeaveTypeEnum: string
{
    case Leave   = 'leave';
    case Mission = 'mission';

    public function label(): string
    {
        return match($this) {
            self::Leave   => 'مرخصی',
            self::Mission => 'ماموریت',
        };
    }
}
