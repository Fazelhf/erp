<?php

declare(strict_types=1);

namespace Modules\Organization\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'branch_id'   => $this->branch_id,
            'parent_id'   => $this->parent_id,
            'manager_id'  => $this->manager_id,
            'name'        => $this->name,
            'code'        => $this->code,
            'is_active'   => $this->is_active,
            'children'    => $this->whenLoaded('children', fn () => DepartmentResource::collection($this->children)),
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
