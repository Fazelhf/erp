<?php

declare(strict_types=1);

namespace Modules\HRM\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'in:approved,rejected'],
            'note'     => ['nullable', 'string', 'max:1000'],
        ];
    }
}
