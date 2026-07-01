<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\CRM\Domain\Customer\Entities\Customer;
use Modules\Notifications\Domain\Notification\InvoiceCreatedNotification;
use Modules\Notifications\Domain\Notification\InvoicePaidNotification;
use Notification;

final class SendInvoiceNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly int    $invoiceId,
        private readonly string $event, // 'created' | 'paid'
    ) {}

    public function handle(): void
    {
        $invoice  = Invoice::find($this->invoiceId);
        $customer = $invoice ? Customer::find($invoice->customer_id) : null;

        if (! $invoice || ! $customer || ! $customer->email) {
            return;
        }

        $notification = match ($this->event) {
            'paid'  => new InvoicePaidNotification($invoice),
            default => new InvoiceCreatedNotification($invoice),
        };

        Notification::route('mail', $customer->email)
            ->notify($notification);
    }
}
