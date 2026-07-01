<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;

final class InvoiceCreatedNotification extends Notification implements ShouldQueue
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
            ->subject("فاکتور #{$this->invoice->invoice_number} صادر شد")
            ->greeting("سلام {$notifiable->name}،")
            ->line("فاکتور جدیدی به شماره {$this->invoice->invoice_number} برای شما صادر شده است.")
            ->line("مبلغ کل: " . number_format((float) $this->invoice->total) . " ریال")
            ->line("تاریخ سررسید: " . $this->invoice->due_date?->format('Y-m-d'))
            ->action('مشاهده فاکتور', url("/invoices/{$this->invoice->id}"))
            ->line('با تشکر');
    }
}
