<?php

declare(strict_types=1);

namespace Modules\Notifications\Infrastructure\Listeners;

use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\Notifications\Application\Jobs\SendInvoiceNotificationJob;

final class DispatchInvoiceNotificationListener
{
    public function handle(object $event): void
    {
        $invoiceId = match (true) {
            $event instanceof InvoiceCreatedEvent => $event->invoice->id,
            $event instanceof InvoicePaidEvent    => $event->invoice->id,
            default => null,
        };

        if (! $invoiceId) {
            return;
        }

        $eventType = $event instanceof InvoicePaidEvent ? 'paid' : 'created';

        SendInvoiceNotificationJob::dispatch($invoiceId, $eventType)
            ->onQueue('notifications');
    }
}
