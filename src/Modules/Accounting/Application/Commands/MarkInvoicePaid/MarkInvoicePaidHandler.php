<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\MarkInvoicePaid;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class MarkInvoicePaidHandler implements CommandHandlerInterface
{
    public function handle(object $command): Invoice
    {
        /** @var MarkInvoicePaidCommand $command */
        $invoice = Invoice::find($command->invoiceId)
            ?? throw InvoiceException::notFound($command->invoiceId);

        if ($invoice->isPaid()) {
            throw InvoiceException::cannotModifyPaid();
        }

        $invoice->update(['status' => InvoiceStatusEnum::Paid]);
        InvoicePaidEvent::dispatch($invoice->fresh());

        return $invoice->fresh();
    }
}
