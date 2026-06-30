<?php

declare(strict_types=1);

namespace App\Modules\Documents\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SendLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string'],
            'to_user_id'  => ['required', 'exists:users,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'attachment'  => ['nullable', 'file', 'mimes:pdf,jpg,png,zip', 'max:2048'],
        ];
    }
}
