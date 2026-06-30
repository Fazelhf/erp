<?php

declare(strict_types=1);

namespace App\Modules\Core\Helpers;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

final class DateHelper
{
    public static function toJalali(mixed $date, string $format = 'Y/m/d'): ?string
    {
        if (! $date) {
            return null;
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return Jalalian::fromCarbon($date)->format($format);
    }

    public static function toGregorian(string $jalaliDate): ?Carbon
    {
        $jalaliDate = trim($jalaliDate);
        $parts = explode('/', $jalaliDate);

        if (count($parts) !== 3) {
            return null;
        }

        try {
            $year = (int) trim($parts[0]);
            $month = (int) trim($parts[1]);
            $day = (int) trim($parts[2]);

            if ($year < 1300 || $year > 1500 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return null;
            }

            $formatted = sprintf('%04d/%02d/%02d', $year, $month, $day);

            return Jalalian::fromFormat('Y/m/d', $formatted)->toCarbon();
        } catch (\Throwable) {
            return null;
        }
    }

    public static function now(string $format = 'Y/m/d'): string
    {
        return Jalalian::now()->format($format);
    }

    public static function today(string $format = 'Y/m/d'): string
    {
        return Jalalian::now()->format($format);
    }
}
