<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Queries\GetInvoice;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetInvoiceHandler implements QueryHandlerInterface
{
    public function handle(object $query): Invoice
    {
        /** @var GetInvoiceQuery $query */
        return Invoice::with('items')->find($query->invoiceId)
            ?? throw InvoiceException::notFound($query->invoiceId);
    }
}
