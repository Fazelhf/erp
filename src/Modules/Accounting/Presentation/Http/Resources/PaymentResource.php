<?php

declare(strict_types=1);

namespace Modules\Accounting\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'company_id'  => $this->company_id,
            'invoice_id'  => $this->invoice_id,
            'amount'      => $this->amount,
            'currency'    => $this->currency,
            'method'      => $this->method,
            'reference'   => $this->reference,
            'status'      => $this->status,
            'paid_at'     => $this->paid_at?->toISOString(),
            'notes'       => $this->notes,
            'created_at'  => $this->created_at?->toISOString(),
        ];
    }
}
