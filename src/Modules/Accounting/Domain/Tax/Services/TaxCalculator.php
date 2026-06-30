<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Tax\Services;

use Modules\Accounting\Domain\Invoice\ValueObjects\Money;

/**
 * Pure domain service — no infrastructure dependencies.
 */
final class TaxCalculator
{
    private const STANDARD_RATE = 0.09;
    private const ZERO_RATE     = 0.00;

    public function standardRate(): float
    {
        return self::STANDARD_RATE;
    }

    public function calculate(Money $base, float $rate = self::STANDARD_RATE): Money
    {
        return $base->multiply($rate);
    }

    public function exempt(Money $base): Money
    {
        return $base->multiply(self::ZERO_RATE);
    }
}
