<?php

declare(strict_types=1);

namespace Modules\HRM\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class LeaveRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'company_id'  => $this->company_id,
            'user_id'     => $this->user_id,
            'type'        => $this->type,
            'start_date'  => $this->start_date?->toDateString(),
            'end_date'    => $this->end_date?->toDateString(),
            'days'        => $this->days,
            'reason'      => $this->reason,
            'status'      => $this->status,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at?->toISOString(),
            'reviewer_note'=> $this->reviewer_note,
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
