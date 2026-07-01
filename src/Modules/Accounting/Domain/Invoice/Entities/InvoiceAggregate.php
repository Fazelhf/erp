<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Entities;

use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Modules\Accounting\Domain\Invoice\ValueObjects\InvoiceLineItem;
use Modules\Accounting\Domain\Invoice\ValueObjects\Money;

/**
 * Invoice Aggregate Root.
 * Enforces all business invariants. No Eloquent dependency.
 * Command handlers reconstitute this from the Eloquent model, call domain methods,
 * then persist changes and publish events themselves.
 */
final class InvoiceAggregate
{
    private InvoiceStatusEnum $status;
    private array $lineItems = [];

    private Money $subtotal;
    private Money $taxAmount;
    private Money $total;

    private function __construct(
        private readonly int    $id,
        private readonly int    $companyId,
        private readonly int    $customerId,
        private readonly string $invoiceNumber,
        private readonly string $currency,
        private readonly float  $invoiceDiscount,
        InvoiceStatusEnum $status,
    ) {
        $this->status    = $status;
        $this->subtotal  = new Money(0, $currency);
        $this->taxAmount = new Money(0, $currency);
        $this->total     = new Money(0, $currency);
    }

    public static function create(
        int    $companyId,
        int    $customerId,
        string $invoiceNumber,
        float  $invoiceDiscount = 0.0,
        string $currency = 'IRR',
    ): self {
        return new self(
            id:              0,
            companyId:       $companyId,
            customerId:      $customerId,
            invoiceNumber:   $invoiceNumber,
            currency:        $currency,
            invoiceDiscount: $invoiceDiscount,
            status:          InvoiceStatusEnum::Draft,
        );
    }

    public static function reconstitute(Invoice $model): self
    {
        $aggregate = new self(
            id:              $model->id,
            companyId:       $model->company_id,
            customerId:      $model->customer_id,
            invoiceNumber:   $model->invoice_number,
            currency:        'IRR',
            invoiceDiscount: (float) $model->discount,
            status:          $model->status,
        );

        foreach ($model->items as $item) {
            $aggregate->lineItems[] = new InvoiceLineItem(
                description: $item->description,
                quantity:    (float) $item->quantity,
                unitPrice:   new Money((float) $item->unit_price, 'IRR'),
                discount:    new Money((float) $item->discount, 'IRR'),
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
    }

    public function cancel(): void
    {
        if (! $this->isCancellable()) {
            throw InvoiceException::cannotModifyPaid();
        }

        $this->status = InvoiceStatusEnum::Cancelled;
    }

    public function isCancellable(): bool
    {
        return ! in_array($this->status, [InvoiceStatusEnum::Paid, InvoiceStatusEnum::Cancelled]);
    }

    public function id(): int                      { return $this->id; }
    public function companyId(): int               { return $this->companyId; }
    public function customerId(): int              { return $this->customerId; }
    public function invoiceNumber(): string        { return $this->invoiceNumber; }
    public function currency(): string             { return $this->currency; }
    public function invoiceDiscount(): float       { return $this->invoiceDiscount; }
    public function status(): InvoiceStatusEnum    { return $this->status; }
    public function lineItems(): array             { return $this->lineItems; }
    public function subtotal(): Money              { return $this->subtotal; }
    public function taxAmount(): Money             { return $this->taxAmount; }
    public function total(): Money                 { return $this->total; }

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

        $discount       = new Money($this->invoiceDiscount, $this->currency);
        $this->subtotal  = $subtotal;
        $this->taxAmount = $tax;
        $this->total     = $subtotal->subtract($discount)->add($tax);
    }
}
