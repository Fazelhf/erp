<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRM\Domain\Leave\Entities\LeaveRequest;

final class LeaveRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly LeaveRequest $leaveRequest,
        private readonly string       $eventType, // 'submitted' | 'approved' | 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return match ($this->eventType) {
            'submitted' => $this->submittedMail($notifiable),
            'approved'  => $this->approvedMail($notifiable),
            'rejected'  => $this->rejectedMail($notifiable),
            default     => $this->submittedMail($notifiable),
        };
    }

    private function submittedMail(object $notifiable): MailMessage
    {
        $req = $this->leaveRequest;

        return (new MailMessage)
            ->subject("درخواست مرخصی جدید — {$req->user?->name}")
            ->greeting("سلام {$notifiable->name}،")
            ->line("درخواست مرخصی جدیدی برای بررسی ارسال شده است.")
            ->line("کارمند: {$req->user?->name}")
            ->line("نوع مرخصی: {$req->type?->value}")
            ->line("از تاریخ: {$req->start_date?->format('Y-m-d')} تا {$req->end_date?->format('Y-m-d')}")
            ->action('بررسی درخواست', url("/leave-requests/{$req->id}"))
            ->line('لطفاً در اسرع وقت بررسی کنید.');
    }

    private function approvedMail(object $notifiable): MailMessage
    {
        $req = $this->leaveRequest;

        return (new MailMessage)
            ->subject('درخواست مرخصی شما تایید شد')
            ->greeting("سلام {$notifiable->name}،")
            ->line('درخواست مرخصی شما تایید شد.')
            ->line("از تاریخ {$req->start_date?->format('Y-m-d')} تا {$req->end_date?->format('Y-m-d')}")
            ->line($req->manager_note ? "یادداشت مدیر: {$req->manager_note}" : '')
            ->line('از همکاری شما متشکریم.');
    }

    private function rejectedMail(object $notifiable): MailMessage
    {
        $req = $this->leaveRequest;

        return (new MailMessage)
            ->subject('درخواست مرخصی شما رد شد')
            ->greeting("سلام {$notifiable->name}،")
            ->line('متأسفانه درخواست مرخصی شما در این بازه تأیید نشد.')
            ->line($req->manager_note ? "دلیل: {$req->manager_note}" : '')
            ->line('برای اطلاعات بیشتر با مدیر خود تماس بگیرید.');
    }
}
