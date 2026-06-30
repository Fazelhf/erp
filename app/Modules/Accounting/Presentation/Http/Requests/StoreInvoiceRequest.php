<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Presentation\Http\Requests;

use App\Modules\Accounting\Domain\Enums\InvoiceStatusEnum;
use App\Modules\Core\Domain\Rules\JalaliDateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'          => ['required', 'exists:customers,id'],
            'invoice_date'         => ['required', new JalaliDateRule()],
            'due_date'             => ['nullable', new JalaliDateRule()],
            'status'               => ['required', Rule::enum(InvoiceStatusEnum::class)],
            'notes'                => ['nullable', 'string'],
            'discount'             => ['nullable', 'numeric', 'min:0'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
            'items.*.unit_price'   => ['required', 'numeric', 'min:0'],
            'items.*.discount'     => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
