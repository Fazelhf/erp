<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\DeleteInvoice;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class DeleteInvoiceHandler implements CommandHandlerInterface
{
    public function handle(object $command): bool
    {
        /** @var DeleteInvoiceCommand $command */
        $invoice = Invoice::find($command->invoiceId)
            ?? throw InvoiceException::notFound($command->invoiceId);

        if ($invoice->isPaid()) {
            throw InvoiceException::cannotDeletePaid();
        }

        return (bool) $invoice->delete();
    }
}
