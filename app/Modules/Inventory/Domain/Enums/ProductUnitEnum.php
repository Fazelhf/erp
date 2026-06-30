<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Domain\Enums;

enum ProductUnitEnum: string
{
    case Piece      = 'عدد';
    case Kilogram   = 'کیلوگرم';
    case Gram       = 'گرم';
    case Meter      = 'متر';
    case SquareMeter = 'متر مربع';
    case Liter      = 'لیتر';
    case Box        = 'جعبه';
    case Set        = 'ست';

    public function label(): string
    {
        return $this->value;
    }
}
