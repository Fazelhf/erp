<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\DeleteInvoice;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Entities\InvoiceAggregate;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class DeleteInvoiceHandler implements CommandHandlerInterface
{
    public function handle(object $command): bool
    {
        /** @var DeleteInvoiceCommand $command */
        $invoice = Invoice::withoutGlobalScopes()->with('items')->find($command->invoiceId)
            ?? throw InvoiceException::notFound($command->invoiceId);

        $aggregate = InvoiceAggregate::reconstitute($invoice);

        if (! $aggregate->isCancellable()) {
            throw InvoiceException::cannotDeletePaid();
        }

        return (bool) $invoice->delete();
    }
}
