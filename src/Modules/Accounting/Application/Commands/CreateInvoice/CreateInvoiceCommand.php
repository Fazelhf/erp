<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\CreateInvoice;

final readonly class CreateInvoiceCommand
{
    public function __construct(
        public int    $companyId,
        public int    $customerId,
        public string $issueDate,
        public string $dueDate,
        public array  $items,
        public float  $discount = 0.0,
        public ?string $notes   = null,
    ) {}
}
