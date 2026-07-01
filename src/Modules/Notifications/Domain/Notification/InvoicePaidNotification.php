<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;

final class InvoicePaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Invoice $invoice) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("پرداخت فاکتور #{$this->invoice->invoice_number} تایید شد")
            ->greeting("سلام {$notifiable->name}،")
            ->line("پرداخت فاکتور {$this->invoice->invoice_number} با موفقیت ثبت شد.")
            ->line("مبلغ دریافتی: " . number_format((float) $this->invoice->total) . " ریال")
            ->action('مشاهده فاکتور', url("/invoices/{$this->invoice->id}"))
            ->line('با تشکر');
    }
}
