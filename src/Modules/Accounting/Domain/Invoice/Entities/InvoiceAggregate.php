<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Entities;

use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCancelledEvent;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Modules\Accounting\Domain\Invoice\ValueObjects\InvoiceLineItem;
use Modules\Accounting\Domain\Invoice\ValueObjects\Money;

/**
 * Invoice Aggregate Root.
 * All mutations go through this class — no external code touches InvoiceItem directly.
 */
final class InvoiceAggregate
{
    private InvoiceStatusEnum $status;
    private array $lineItems = [];
    private array $domainEvents = [];

    private Money $subtotal;
    private Money $taxAmount;
    private Money $total;

    private function __construct(
        private readonly int    $id,
        private readonly int    $companyId,
        private readonly int    $customerId,
        private readonly string $invoiceNumber,
        private readonly string $currency,
        InvoiceStatusEnum $status,
    ) {
        $this->status   = $status;
        $this->subtotal = new Money(0, $currency);
        $this->taxAmount = new Money(0, $currency);
        $this->total    = new Money(0, $currency);
    }

    public static function create(
        int    $companyId,
        int    $customerId,
        string $invoiceNumber,
        string $currency = 'IRR',
    ): self {
        $aggregate = new self(
            id:            0,
            companyId:     $companyId,
            customerId:    $customerId,
            invoiceNumber: $invoiceNumber,
            currency:      $currency,
            status:        InvoiceStatusEnum::Draft,
        );

        $aggregate->recordEvent(new InvoiceCreatedEvent($aggregate));

        return $aggregate;
    }

    public static function reconstitute(Invoice $model): self
    {
        $aggregate = new self(
            id:            $model->id,
            companyId:     $model->company_id,
            customerId:    $model->customer_id,
            invoiceNumber: $model->invoice_number,
            currency:      'IRR',
            status:        $model->status,
        );

        foreach ($model->items as $item) {
            $aggregate->lineItems[] = new InvoiceLineItem(
                description: $item->description,
                quantity:    (float) $item->quantity,
                unitPrice:   new Money((float) $item->unit_price),
                discount:    new Money((float) $item->discount),
                taxRate:     (float) $item->tax_rate,
                productId:   $item->product_id,
            );
        }

        $aggregate->recalculate();

        return $aggregate;
    }

    public function addLineItem(InvoiceLineItem $item): void
    {
        $this->assertModifiable();
        $this->lineItems[] = $item;
        $this->recalculate();
    }

    public function markPaid(): void
    {
        if ($this->status === InvoiceStatusEnum::Paid) {
            throw InvoiceException::cannotModifyPaid();
        }

        if ($this->status === InvoiceStatusEnum::Cancelled) {
            throw new \DomainException('Cannot pay a cancelled invoice.');
        }

        $this->status = InvoiceStatusEnum::Paid;
        $this->recordEvent(new InvoicePaidEvent($this));
    }

    public function cancel(): void
    {
        if (!$this->isCancellable()) {
            throw InvoiceException::cannotModifyPaid();
        }

        $this->status = InvoiceStatusEnum::Cancelled;
        $this->recordEvent(new InvoiceCancelledEvent($this));
    }

    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }

    public function isCancellable(): bool
    {
        return !in_array($this->status, [InvoiceStatusEnum::Paid, InvoiceStatusEnum::Cancelled]);
    }

    public function id(): int            { return $this->id; }
    public function companyId(): int     { return $this->companyId; }
    public function customerId(): int    { return $this->customerId; }
    public function invoiceNumber(): string { return $this->invoiceNumber; }
    public function status(): InvoiceStatusEnum { return $this->status; }
    public function lineItems(): array   { return $this->lineItems; }
    public function subtotal(): Money    { return $this->subtotal; }
    public function taxAmount(): Money   { return $this->taxAmount; }
    public function total(): Money       { return $this->total; }

    private function assertModifiable(): void
    {
        if (in_array($this->status, [InvoiceStatusEnum::Paid, InvoiceStatusEnum::Cancelled])) {
            throw InvoiceException::cannotModifyPaid();
        }
    }

    private function recalculate(): void
    {
        $subtotal = new Money(0, $this->currency);
        $tax      = new Money(0, $this->currency);

        foreach ($this->lineItems as $item) {
            $subtotal = $subtotal->add($item->gross);
            $tax      = $tax->add($item->taxAmount);
        }

        $this->subtotal  = $subtotal;
        $this->taxAmount = $tax;
        $this->total     = $subtotal->add($tax);
    }

    private function recordEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }
}
