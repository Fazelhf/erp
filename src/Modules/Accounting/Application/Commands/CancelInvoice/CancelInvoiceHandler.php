<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\CancelInvoice;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Entities\InvoiceAggregate;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCancelledEvent;
use Modules\Accounting\Domain\Invoice\Exceptions\InvoiceException;
use Shared\Application\Bus\EventBusInterface;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CancelInvoiceHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function handle(object $command): Invoice
    {
        /** @var CancelInvoiceCommand $command */
        $invoice = Invoice::withoutGlobalScopes()->with('items')->find($command->invoiceId)
            ?? throw InvoiceException::notFound($command->invoiceId);

        $aggregate = InvoiceAggregate::reconstitute($invoice);
        $aggregate->cancel();

        $invoice->update(['status' => InvoiceStatusEnum::Cancelled]);
        $invoice = $invoice->fresh();

        $this->eventBus->publish(new InvoiceCancelledEvent($invoice));

        return $invoice;
    }
}
