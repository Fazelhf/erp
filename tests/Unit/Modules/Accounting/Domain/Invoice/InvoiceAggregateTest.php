<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Accounting\Domain\Invoice;

use Modules\Accounting\Domain\Invoice\Entities\InvoiceAggregate;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCancelledEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Modules\Accounting\Domain\Invoice\ValueObjects\InvoiceLineItem;
use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

final class InvoiceAggregateTest extends TestCase
{
    private function makeAggregate(): InvoiceAggregate
    {
        return InvoiceAggregate::create(
            companyId:     1,
            customerId:    42,
            invoiceNumber: 'INV-001',
            currency:      'IRR',
        );
    }

    private function makeLine(float $price = 1000.0, float $qty = 1.0, float $taxRate = 0.09): InvoiceLineItem
    {
        return new InvoiceLineItem(
            description: 'Test Item',
            quantity:    $qty,
            unitPrice:   new Money($price, 'IRR'),
            discount:    new Money(0.0, 'IRR'),
            taxRate:     $taxRate,
        );
    }

    // ── Creation ──────────────────────────────────────────────────────────────

    public function test_create_sets_draft_status(): void
    {
        $aggregate = $this->makeAggregate();

        $this->assertSame(InvoiceStatusEnum::Draft, $aggregate->status());
    }

    public function test_create_records_invoice_created_event(): void
    {
        $aggregate = $this->makeAggregate();
        $events    = $aggregate->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(InvoiceCreatedEvent::class, $events[0]);
    }

    public function test_release_events_clears_the_queue(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();

        $this->assertEmpty($aggregate->releaseEvents());
    }

    // ── Line items ────────────────────────────────────────────────────────────

    public function test_add_line_item_updates_totals(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();

        $aggregate->addLineItem($this->makeLine(1000.0, 1, 0.09));

        $this->assertEqualsWithDelta(1000.0, $aggregate->subtotal()->amount, 0.001);
        $this->assertEqualsWithDelta(90.0,   $aggregate->taxAmount()->amount, 0.001);
        $this->assertEqualsWithDelta(1090.0, $aggregate->total()->amount, 0.001);
    }

    public function test_multiple_line_items_accumulate_totals(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();

        $aggregate->addLineItem($this->makeLine(1000.0, 1, 0.0));
        $aggregate->addLineItem($this->makeLine(500.0, 2, 0.0));

        $this->assertSame(2000.0, $aggregate->subtotal()->amount);
    }

    public function test_cannot_add_line_item_to_paid_invoice(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->addLineItem($this->makeLine());
        $aggregate->releaseEvents();
        $aggregate->markPaid();

        $this->expectException(InvoiceException::class);

        $aggregate->addLineItem($this->makeLine());
    }

    public function test_cannot_add_line_item_to_cancelled_invoice(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();
        $aggregate->cancel();

        $this->expectException(InvoiceException::class);

        $aggregate->addLineItem($this->makeLine());
    }

    // ── Status transitions ────────────────────────────────────────────────────

    public function test_mark_paid_changes_status_to_paid(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->addLineItem($this->makeLine());
        $aggregate->releaseEvents();

        $aggregate->markPaid();

        $this->assertSame(InvoiceStatusEnum::Paid, $aggregate->status());
    }

    public function test_mark_paid_records_invoice_paid_event(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->addLineItem($this->makeLine());
        $aggregate->releaseEvents();

        $aggregate->markPaid();
        $events = $aggregate->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(InvoicePaidEvent::class, $events[0]);
    }

    public function test_cannot_pay_an_already_paid_invoice(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->addLineItem($this->makeLine());
        $aggregate->releaseEvents();
        $aggregate->markPaid();

        $this->expectException(InvoiceException::class);

        $aggregate->markPaid();
    }

    public function test_cancel_changes_status_to_cancelled(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();

        $aggregate->cancel();

        $this->assertSame(InvoiceStatusEnum::Cancelled, $aggregate->status());
    }

    public function test_cancel_records_invoice_cancelled_event(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();

        $aggregate->cancel();
        $events = $aggregate->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(InvoiceCancelledEvent::class, $events[0]);
    }

    public function test_cannot_cancel_a_paid_invoice(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->addLineItem($this->makeLine());
        $aggregate->releaseEvents();
        $aggregate->markPaid();

        $this->expectException(InvoiceException::class);

        $aggregate->cancel();
    }

    public function test_cannot_pay_a_cancelled_invoice(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->releaseEvents();
        $aggregate->cancel();

        $this->expectException(\DomainException::class);

        $aggregate->markPaid();
    }

    // ── is_cancellable ────────────────────────────────────────────────────────

    public function test_is_cancellable_returns_true_for_draft(): void
    {
        $this->assertTrue($this->makeAggregate()->isCancellable());
    }

    public function test_is_cancellable_returns_false_for_paid(): void
    {
        $aggregate = $this->makeAggregate();
        $aggregate->addLineItem($this->makeLine());
        $aggregate->releaseEvents();
        $aggregate->markPaid();

        $this->assertFalse($aggregate->isCancellable());
    }
}
