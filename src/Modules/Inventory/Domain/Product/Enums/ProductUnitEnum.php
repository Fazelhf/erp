<?php

declare(strict_types=1);

namespace Modules\Inventory\Domain\Product\Enums;

enum ProductUnitEnum: string
{
    case Piece      = 'piece';
    case Kilogram   = 'kg';
    case Gram       = 'gram';
    case Liter      = 'liter';
    case Meter      = 'meter';
    case Box        = 'box';
    case Set        = 'set';
    case Hour       = 'hour';

    public function label(): string
    {
        return match($this) {
            self::Piece    => 'عدد',
            self::Kilogram => 'کیلوگرم',
            self::Gram     => 'گرم',
            self::Liter    => 'لیتر',
            self::Meter    => 'متر',
            self::Box      => 'جعبه',
            self::Set      => 'ست',
            self::Hour     => 'ساعت',
        };
    }
}
