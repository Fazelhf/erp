<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Services;

use Modules\Accounting\Domain\Invoice\ValueObjects\Money;

final class DiscountCalculator
{
    public function percentage(Money $base, float $percentage): Money
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \DomainException("Discount percentage must be between 0 and 100.");
        }

        return $base->multiply($percentage / 100);
    }

    public function fixed(Money $discount, Money $base): Money
    {
        if ($discount->amount > $base->amount) {
            throw new \DomainException("Fixed discount cannot exceed base amount.");
        }

        return $discount;
    }
}
