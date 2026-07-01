<?php

declare(strict_types=1);

namespace Modules\Audit\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Invoice\Events\InvoicePaidEvent;
use Modules\Audit\Infrastructure\Listeners\RecordAuditLogListener;
use Modules\CRM\Domain\Customer\Events\CustomerCreatedEvent;
use Modules\HRM\Domain\Leave\Events\LeaveRequestSubmittedEvent;
use Modules\IAM\Domain\User\Events\UserCreatedEvent;
use Shared\Application\Bus\EventBusInterface;

class AuditServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $bus = $this->app->make(EventBusInterface::class);

        foreach ($this->eventListeners() as $event) {
            $bus->subscribe($event, RecordAuditLogListener::class);
        }
    }

    private function eventListeners(): array
    {
        return [
            InvoiceCreatedEvent::class,
            InvoicePaidEvent::class,
            CustomerCreatedEvent::class,
            UserCreatedEvent::class,
            LeaveRequestSubmittedEvent::class,
        ];
    }
}
