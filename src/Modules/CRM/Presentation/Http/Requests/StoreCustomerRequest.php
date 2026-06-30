<?php

declare(strict_types=1);

namespace Modules\CRM\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\CRM\Domain\Customer\Enums\CustomerTypeEnum;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'        => ['required', Rule::enum(CustomerTypeEnum::class)],
            'name'        => ['required', 'string', 'max:255'],
            'national_id' => ['nullable', 'string', 'max:20'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'email'       => ['nullable', 'email', 'max:255'],
            'address'     => ['nullable', 'string', 'max:1000'],
        ];
    }
}
