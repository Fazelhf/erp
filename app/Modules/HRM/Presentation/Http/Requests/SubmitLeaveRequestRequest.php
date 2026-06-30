<?php

declare(strict_types=1);

namespace App\Modules\HRM\Presentation\Http\Requests;

use App\Modules\HRM\Domain\Enums\LeaveTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SubmitLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'       => ['required', Rule::enum(LeaveTypeEnum::class)],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['required', 'string', 'max:1000'],
        ];
    }
}
