<?php

declare(strict_types=1);

namespace Modules\HRM\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\HRM\Domain\Leave\Enums\LeaveTypeEnum;

class SubmitLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'integer', 'exists:users,id'],
            'type'       => ['required', Rule::enum(LeaveTypeEnum::class)],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['required', 'string', 'max:1000'],
        ];
    }
}
