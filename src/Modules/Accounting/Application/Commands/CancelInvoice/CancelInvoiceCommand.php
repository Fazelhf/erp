<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\CancelInvoice;

final readonly class CancelInvoiceCommand
{
    public function __construct(public int $invoiceId) {}
}
