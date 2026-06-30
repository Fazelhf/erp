<?php

declare(strict_types=1);

namespace Modules\Organization\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'company_id'       => $this->company_id,
            'name'             => $this->name,
            'code'             => $this->code,
            'is_headquarters'  => $this->is_headquarters,
            'phone'            => $this->phone,
            'address'          => $this->address,
            'is_active'        => $this->is_active,
            'created_at'       => $this->created_at?->toISOString(),
        ];
    }
}
