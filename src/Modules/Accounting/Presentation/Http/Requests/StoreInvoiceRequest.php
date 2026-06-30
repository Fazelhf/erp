<?php

declare(strict_types=1);

namespace Modules\Accounting\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'          => ['required', 'integer', 'exists:customers,id'],
            'issue_date'           => ['required', 'date'],
            'due_date'             => ['required', 'date', 'after_or_equal:issue_date'],
            'discount'             => ['nullable', 'numeric', 'min:0'],
            'notes'                => ['nullable', 'string', 'max:2000'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.description'  => ['required', 'string', 'max:500'],
            'items.*.quantity'     => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'   => ['required', 'numeric', 'min:0'],
            'items.*.discount'     => ['nullable', 'numeric', 'min:0'],
            'items.*.product_id'   => ['nullable', 'integer', 'exists:products,id'],
        ];
    }
}
