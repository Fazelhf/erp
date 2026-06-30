<?php

declare(strict_types=1);

namespace Modules\Inventory\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'company_id'  => $this->company_id,
            'name'        => $this->name,
            'sku'         => $this->sku,
            'description' => $this->description,
            'unit'        => $this->unit,
            'price'       => $this->price,
            'cost_price'  => $this->cost_price,
            'tax_rate'    => $this->tax_rate,
            'stock'       => $this->stock,
            'is_active'   => $this->is_active,
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
