<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\DeleteInvoice;

final readonly class DeleteInvoiceCommand
{
    public function __construct(public int $invoiceId) {}
}
