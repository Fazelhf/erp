<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\MarkInvoicePaid;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Shared\Application\Bus\EventBusInterface;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class MarkInvoicePaidHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function handle(object $command): Invoice
    {
        /** @var MarkInvoicePaidCommand $command */
        $invoice = Invoice::withoutGlobalScopes()->find($command->invoiceId)
            ?? throw InvoiceException::notFound($command->invoiceId);

        if ($invoice->isPaid()) {
            throw InvoiceException::cannotModifyPaid();
        }

        $invoice->update(['status' => InvoiceStatusEnum::Paid]);
        $invoice = $invoice->fresh();

        $this->eventBus->publish(new InvoicePaidEvent($invoice));

        return $invoice;
    }
}
