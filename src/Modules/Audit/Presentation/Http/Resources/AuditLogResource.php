<?php

declare(strict_types=1);

namespace Modules\Audit\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'company_id'     => $this->company_id,
            'user_id'        => $this->user_id,
            'action'         => $this->action,
            'auditable_type' => $this->auditable_type,
            'auditable_id'   => $this->auditable_id,
            'old_values'     => $this->old_values,
            'new_values'     => $this->new_values,
            'ip_address'     => $this->ip_address,
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}
