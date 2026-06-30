<?php

declare(strict_types=1);

namespace Modules\CRM\Domain\Customer\Enums;

enum CustomerTypeEnum: string
{
    case Individual = 'individual';
    case Company    = 'company';

    public function label(): string
    {
        return match($this) {
            self::Individual => 'حقیقی',
            self::Company    => 'حقوقی',
        };
    }
}
