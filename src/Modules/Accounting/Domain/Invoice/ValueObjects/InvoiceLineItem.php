<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\ValueObjects;

final readonly class InvoiceLineItem
{
    public Money $gross;
    public Money $taxAmount;
    public Money $total;

    public function __construct(
        public string  $description,
        public float   $quantity,
        public Money   $unitPrice,
        public Money   $discount,
        public float   $taxRate = 0.09,
        public ?int    $productId = null,
    ) {
        if ($quantity <= 0) {
            throw new \DomainException("Quantity must be positive.");
        }

        $this->gross     = $unitPrice->multiply($quantity)->subtract($discount);
        $this->taxAmount = $this->gross->multiply($taxRate);
        $this->total     = $this->gross->add($this->taxAmount);
    }
}
