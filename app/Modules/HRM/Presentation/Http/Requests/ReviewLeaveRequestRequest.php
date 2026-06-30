<?php

declare(strict_types=1);

namespace App\Modules\HRM\Presentation\Http\Requests;

use App\Modules\HRM\Domain\Enums\LeaveStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(LeaveStatusEnum::class)],
        ];
    }
}
