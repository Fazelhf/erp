<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Queries\GetInvoice;

final readonly class GetInvoiceQuery
{
    public function __construct(public int $invoiceId) {}
}
