<?php

declare(strict_types=1);

namespace Modules\Audit\Infrastructure\Listeners;

use Modules\Audit\Domain\AuditLog\Entities\AuditLog;

final class RecordAuditLogListener
{
    public function handle(object $event): void
    {
        $payload = $this->extractPayload($event);

        if ($payload === null) {
            return;
        }

        AuditLog::create([
            'company_id'     => auth()->user()?->company_id,
            'user_id'        => auth()->id(),
            'action'         => $payload['action'],
            'auditable_type' => $payload['type'],
            'auditable_id'   => $payload['id'],
            'old_values'     => [],
            'new_values'     => $payload['attributes'],
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }

    private function extractPayload(object $event): ?array
    {
        return match (true) {
            isset($event->invoice) => [
                'action'     => 'created',
                'type'       => 'invoice',
                'id'         => $event->invoice->id,
                'attributes' => ['invoice_number' => $event->invoice->invoice_number ?? null],
            ],
            isset($event->customer) => [
                'action'     => 'created',
                'type'       => 'customer',
                'id'         => $event->customer->id,
                'attributes' => ['name' => $event->customer->name],
            ],
            isset($event->user) => [
                'action'     => 'created',
                'type'       => 'user',
                'id'         => $event->user->id,
                'attributes' => ['email' => $event->user->email],
            ],
            isset($event->leaveRequest) => [
                'action'     => 'created',
                'type'       => 'leave_request',
                'id'         => $event->leaveRequest->id,
                'attributes' => ['type' => $event->leaveRequest->type ?? null],
            ],
            default => null,
        };
    }
}
