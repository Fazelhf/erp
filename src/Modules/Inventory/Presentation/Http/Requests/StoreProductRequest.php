<?php

declare(strict_types=1);

namespace Modules\Inventory\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Inventory\Domain\Product\Enums\ProductUnitEnum;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'sku'             => ['nullable', 'string', 'max:100'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'unit'            => ['required', Rule::enum(ProductUnitEnum::class)],
            'price'           => ['required', 'numeric', 'min:0'],
            'cost_price'      => ['nullable', 'numeric', 'min:0'],
            'stock_quantity'  => ['nullable', 'numeric', 'min:0'],
            'min_stock_level' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
