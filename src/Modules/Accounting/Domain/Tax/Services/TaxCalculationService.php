<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Tax\Services;

final class TaxCalculationService
{
    private const DEFAULT_TAX_RATE = 0.09;

    public function calculateItemTotal(
        float $unitPrice,
        float $quantity,
        float $discount = 0.0,
        float $taxRate  = self::DEFAULT_TAX_RATE,
    ): array {
        $gross    = $unitPrice * $quantity;
        $afterDisc = $gross - $discount;
        $taxAmount = $afterDisc * $taxRate;
        $total     = $afterDisc + $taxAmount;

        return [
            'gross'      => round($gross, 2),
            'discount'   => round($discount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total'      => round($total, 2),
            'tax_rate'   => $taxRate,
        ];
    }

    public function calculateInvoiceTotals(array $items, float $invoiceDiscount = 0.0): array
    {
        $subtotal   = array_sum(array_column($items, 'gross'));
        $taxAmount  = array_sum(array_column($items, 'tax_amount'));
        $itemDiscounts = array_sum(array_column($items, 'discount'));
        $total      = $subtotal - $itemDiscounts - $invoiceDiscount + $taxAmount;

        return [
            'subtotal'   => round($subtotal, 2),
            'discount'   => round($invoiceDiscount, 2),
            'tax_amount' => round($taxAmount, 2),
            'total'      => round($total, 2),
        ];
    }
}
