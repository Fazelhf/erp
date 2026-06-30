<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Application\DTOs;

final readonly class InvoiceItemData
{
    public function __construct(
        public int    $productId,
        public int    $quantity,
        public float  $unitPrice,
        public float  $discount = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: (int) $data['product_id'],
            quantity:  (int) $data['quantity'],
            unitPrice: (float) $data['unit_price'],
            discount:  (float) ($data['discount'] ?? 0),
        );
    }
}
