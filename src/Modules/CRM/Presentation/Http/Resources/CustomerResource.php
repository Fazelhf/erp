<?php

declare(strict_types=1);

namespace Modules\CRM\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'company_id'  => $this->company_id,
            'type'        => $this->type,
            'name'        => $this->name,
            'trade_name'  => $this->trade_name,
            'national_id' => $this->national_id,
            'tax_id'      => $this->tax_id,
            'phone'       => $this->phone,
            'email'       => $this->email,
            'address'     => $this->address,
            'credit_limit'=> $this->credit_limit,
            'is_active'   => $this->is_active,
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
