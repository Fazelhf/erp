<?php

declare(strict_types=1);

namespace Modules\Notifications\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\HRM\Domain\Leave\Events\LeaveRequestSubmittedEvent;
use Modules\Notifications\Infrastructure\Listeners\DispatchInvoiceNotificationListener;
use Modules\Notifications\Infrastructure\Listeners\DispatchLeaveNotificationListener;
use Modules\Notifications\Infrastructure\Listeners\DispatchWorkflowNotificationListener;
use Modules\Workflow\Domain\Pipeline\Events\WorkflowAdvancedEvent;
use Shared\Application\Bus\EventBusInterface;

class NotificationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $bus = $this->app->make(EventBusInterface::class);

        $bus->subscribe(InvoiceCreatedEvent::class,       DispatchInvoiceNotificationListener::class);
        $bus->subscribe(InvoicePaidEvent::class,          DispatchInvoiceNotificationListener::class);
        $bus->subscribe(LeaveRequestSubmittedEvent::class, DispatchLeaveNotificationListener::class);
        $bus->subscribe(WorkflowAdvancedEvent::class,     DispatchWorkflowNotificationListener::class);
    }
}
