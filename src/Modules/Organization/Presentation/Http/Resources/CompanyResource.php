<?php

declare(strict_types=1);

namespace Modules\Organization\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'tenant_id'         => $this->tenant_id,
            'name'              => $this->name,
            'legal_name'        => $this->legal_name,
            'tax_id'            => $this->tax_id,
            'registration_no'   => $this->registration_no,
            'currency'          => $this->currency,
            'fiscal_year_start' => $this->fiscal_year_start,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'website'           => $this->website,
            'address'           => $this->address,
            'is_active'         => $this->is_active,
            'created_at'        => $this->created_at?->toISOString(),
        ];
    }
}
