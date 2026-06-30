<?php

declare(strict_types=1);

namespace Modules\Reporting\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'company_id'   => $this->company_id,
            'created_by'   => $this->created_by,
            'type'         => $this->type,
            'name'         => $this->name,
            'format'       => $this->format,
            'filters'      => $this->filters,
            'file_path'    => $this->file_path,
            'generated_at' => $this->generated_at?->toISOString(),
            'expires_at'   => $this->expires_at?->toISOString(),
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }
}
