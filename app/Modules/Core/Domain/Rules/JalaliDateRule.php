<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Rules;

use App\Modules\Core\Helpers\DateHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class JalaliDateRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $value)) {
            $fail('فرمت تاریخ باید به صورت YYYY/MM/DD باشد.');

            return;
        }

        if (DateHelper::toGregorian($value) === null) {
            $fail('تاریخ شمسی وارد شده معتبر نیست.');
        }
    }
}
