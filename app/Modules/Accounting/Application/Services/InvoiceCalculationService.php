<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Application\Services;

use App\Modules\Accounting\Application\DTOs\InvoiceItemData;

final class InvoiceCalculationService
{
    private const TAX_RATE = 0.09;

    public function calculateItem(InvoiceItemData $item): array
    {
        $lineTotal = ($item->unitPrice * $item->quantity) - $item->discount;
        $tax       = $lineTotal * self::TAX_RATE;
        $total     = $lineTotal + $tax;

        return [
            'subtotal' => $lineTotal,
            'tax'      => round($tax, 2),
            'total'    => round($total, 2),
        ];
    }

    public function calculateTotals(array $itemResults, float $invoiceDiscount): array
    {
        $subtotal = array_sum(array_column($itemResults, 'subtotal'));
        $taxable  = $subtotal - $invoiceDiscount;
        $tax      = $taxable * self::TAX_RATE;
        $total    = $taxable + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($invoiceDiscount, 2),
            'tax'      => round($tax, 2),
            'total'    => round($total, 2),
        ];
    }

    public function taxRate(): float
    {
        return self::TAX_RATE;
    }
}
