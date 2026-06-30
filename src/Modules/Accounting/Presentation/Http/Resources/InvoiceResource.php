<?php

declare(strict_types=1);

namespace Modules\Accounting\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'company_id'     => $this->company_id,
            'customer_id'    => $this->customer_id,
            'invoice_number' => $this->invoice_number,
            'status'         => $this->status,
            'issue_date'     => $this->issue_date?->toDateString(),
            'due_date'       => $this->due_date?->toDateString(),
            'subtotal'       => $this->subtotal,
            'discount'       => $this->discount,
            'tax_amount'     => $this->tax_amount,
            'total'          => $this->total,
            'currency'       => $this->currency,
            'notes'          => $this->notes,
            'items'          => $this->whenLoaded('items'),
            'customer'       => $this->whenLoaded('customer'),
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}
