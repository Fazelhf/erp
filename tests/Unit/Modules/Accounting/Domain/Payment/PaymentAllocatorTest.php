<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Accounting\Domain\Payment;

use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use Modules\Accounting\Domain\Payment\Services\PaymentAllocator;
use PHPUnit\Framework\TestCase;

final class PaymentAllocatorTest extends TestCase
{
    private PaymentAllocator $allocator;

    protected function setUp(): void
    {
        $this->allocator = new PaymentAllocator();
    }

    public function test_full_payment_allocates_to_single_invoice(): void
    {
        $result = $this->allocator->allocate(
            new Money(1000.0, 'IRR'),
            [['invoice_id' => 1, 'outstanding' => new Money(1000.0, 'IRR')]],
        );

        $this->assertCount(1, $result);
        $this->assertSame(1, $result[0]['invoice_id']);
        $this->assertSame(1000.0, $result[0]['allocated']->amount);
        $this->assertSame(0.0,    $result[0]['remaining_outstanding']->amount);
    }

    public function test_partial_payment_leaves_remainder_on_invoice(): void
    {
        $result = $this->allocator->allocate(
            new Money(400.0, 'IRR'),
            [['invoice_id' => 1, 'outstanding' => new Money(1000.0, 'IRR')]],
        );

        $this->assertSame(400.0, $result[0]['allocated']->amount);
        $this->assertSame(600.0, $result[0]['remaining_outstanding']->amount);
    }

    public function test_payment_distributes_across_multiple_invoices(): void
    {
        $result = $this->allocator->allocate(
            new Money(1500.0, 'IRR'),
            [
                ['invoice_id' => 1, 'outstanding' => new Money(1000.0, 'IRR')],
                ['invoice_id' => 2, 'outstanding' => new Money(1000.0, 'IRR')],
            ],
        );

        $this->assertCount(2, $result);
        $this->assertSame(1000.0, $result[0]['allocated']->amount);
        $this->assertSame(500.0,  $result[1]['allocated']->amount);
    }

    public function test_excess_payment_stops_after_all_invoices_settled(): void
    {
        $result = $this->allocator->allocate(
            new Money(3000.0, 'IRR'),
            [
                ['invoice_id' => 1, 'outstanding' => new Money(500.0, 'IRR')],
                ['invoice_id' => 2, 'outstanding' => new Money(500.0, 'IRR')],
            ],
        );

        $this->assertCount(2, $result);
        $this->assertSame(500.0, $result[0]['allocated']->amount);
        $this->assertSame(500.0, $result[1]['allocated']->amount);
    }

    public function test_zero_payment_allocates_nothing(): void
    {
        $result = $this->allocator->allocate(
            new Money(0.0, 'IRR'),
            [['invoice_id' => 1, 'outstanding' => new Money(1000.0, 'IRR')]],
        );

        $this->assertEmpty($result);
    }

    public function test_empty_invoice_list_returns_empty_allocations(): void
    {
        $result = $this->allocator->allocate(new Money(500.0, 'IRR'), []);

        $this->assertEmpty($result);
    }
}
