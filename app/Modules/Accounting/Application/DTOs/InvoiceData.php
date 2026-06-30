<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Application\DTOs;

use App\Modules\Accounting\Domain\Enums\InvoiceStatusEnum;

final readonly class InvoiceData
{
    /**
     * @param InvoiceItemData[] $items
     */
    public function __construct(
        public int                $customerId,
        public string             $invoiceDate,
        public ?string            $dueDate,
        public InvoiceStatusEnum  $status,
        public ?string            $notes,
        public float              $discount,
        public array              $items,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerId:  (int) $data['customer_id'],
            invoiceDate: $data['invoice_date'],
            dueDate:     $data['due_date'] ?? null,
            status:      InvoiceStatusEnum::from($data['status']),
            notes:       $data['notes'] ?? null,
            discount:    (float) ($data['discount'] ?? 0),
            items:       array_map(
                fn (array $item) => InvoiceItemData::fromArray($item),
                $data['items'] ?? []
            ),
        );
    }
}
