<?php

declare(strict_types=1);

namespace App\Modules\CRM\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'company_name'  => ['nullable', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'mobile'        => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string'],
            'national_id'   => ['nullable', 'string', 'max:20'],
            'economic_code' => ['nullable', 'string', 'max:20'],
            'notes'         => ['nullable', 'string'],
            'is_active'     => ['boolean'],
        ];
    }
}
