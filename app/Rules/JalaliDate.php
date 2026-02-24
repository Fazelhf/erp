<?php

namespace App\Rules;

use App\Helpers\DateHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JalaliDate implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value) {
            return;
        }

        $gregorian = DateHelper::toGregorian($value);
        
        if (!$gregorian) {
            $fail('فرمت تاریخ :attribute معتبر نیست. لطفاً تاریخ را به فرمت شمسی وارد کنید (مثال: 1403/01/01)');
        }
    }
}

