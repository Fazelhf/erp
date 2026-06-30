<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Accounting\Domain\Invoice;

use Modules\Accounting\Domain\Invoice\ValueObjects\InvoiceLineItem;
use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

final class InvoiceLineItemTest extends TestCase
{
    public function test_calculates_gross_as_quantity_times_price_minus_discount(): void
    {
        $item = new InvoiceLineItem(
            description: 'Widget',
            quantity:    2,
            unitPrice:   new Money(1000.0, 'IRR'),
            discount:    new Money(100.0, 'IRR'),
            taxRate:     0.0,
        );

        // gross = (2 * 1000) - 100 = 1900
        $this->assertSame(1900.0, $item->gross->amount);
    }

    public function test_calculates_tax_amount_from_gross(): void
    {
        $item = new InvoiceLineItem(
            description: 'Widget',
            quantity:    1,
            unitPrice:   new Money(1000.0, 'IRR'),
            discount:    new Money(0.0, 'IRR'),
            taxRate:     0.09,
        );

        $this->assertEqualsWithDelta(90.0, $item->taxAmount->amount, 0.001);
    }

    public function test_total_equals_gross_plus_tax(): void
    {
        $item = new InvoiceLineItem(
            description: 'Widget',
            quantity:    1,
            unitPrice:   new Money(1000.0, 'IRR'),
            discount:    new Money(0.0, 'IRR'),
            taxRate:     0.09,
        );

        $this->assertEqualsWithDelta(1090.0, $item->total->amount, 0.001);
    }

    public function test_zero_tax_rate_produces_zero_tax(): void
    {
        $item = new InvoiceLineItem(
            description: 'Exempt Item',
            quantity:    1,
            unitPrice:   new Money(500.0, 'IRR'),
            discount:    new Money(0.0, 'IRR'),
            taxRate:     0.0,
        );

        $this->assertSame(0.0, $item->taxAmount->amount);
        $this->assertSame(500.0, $item->total->amount);
    }

    public function test_fractional_quantity_is_supported(): void
    {
        $item = new InvoiceLineItem(
            description: 'Half unit',
            quantity:    0.5,
            unitPrice:   new Money(200.0, 'IRR'),
            discount:    new Money(0.0, 'IRR'),
            taxRate:     0.0,
        );

        $this->assertSame(100.0, $item->gross->amount);
    }
}
