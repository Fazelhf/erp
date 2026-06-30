<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'company_id'  => $this->company_id,
            'is_system'   => $this->is_system,
            'permissions' => $this->whenLoaded('permissions', fn () => PermissionResource::collection($this->permissions)),
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
