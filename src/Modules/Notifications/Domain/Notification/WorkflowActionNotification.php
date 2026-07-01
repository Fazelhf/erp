<?php

declare(strict_types=1);

namespace Modules\Notifications\Domain\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Workflow\Domain\Pipeline\Entities\WorkflowInstance;
use Modules\Workflow\Domain\Transition\Entities\WorkflowTransition;

final class WorkflowActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly WorkflowInstance   $instance,
        private readonly WorkflowTransition $transition,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $decision    = $this->transition->decision;
        $subjectType = class_basename($this->instance->subject_type);
        $status      = $this->instance->status?->value ?? 'unknown';

        return (new MailMessage)
            ->subject("بروزرسانی فرآیند: {$this->instance->definition?->name}")
            ->greeting("سلام {$notifiable->name}،")
            ->line("وضعیت فرآیند «{$this->instance->definition?->name}» تغییر کرد.")
            ->line("تصمیم: {$decision}")
            ->line("وضعیت جاری: {$status}")
            ->when($this->transition->comment, fn ($m) => $m->line("توضیح: {$this->transition->comment}"))
            ->action('مشاهده جزئیات', url("/workflows/{$this->instance->id}"))
            ->line('با تشکر');
    }
}
