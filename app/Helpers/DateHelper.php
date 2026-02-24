<?php

namespace App\Helpers;

use Morilog\Jalali\Jalalian;

class DateHelper
{
    public static function toJalali($date, $format = 'Y/m/d')
    {
        if (!$date) {
            return null;
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return Jalalian::fromCarbon($date)->format($format);
    }

    public static function toGregorian($jalaliDate)
    {
        if (!$jalaliDate) {
            return null;
        }

        $jalaliDate = trim($jalaliDate);
        $parts = explode('/', $jalaliDate);
        
        if (count($parts) !== 3) {
            return null;
        }

        try {
            $year = (int)trim($parts[0]);
            $month = (int)trim($parts[1]);
            $day = (int)trim($parts[2]);
            
            if ($year < 1300 || $year > 1500 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return null;
            }
            
            // استفاده از fromFormat برای تبدیل تاریخ شمسی به میلادی
            $jalaliDateString = sprintf('%04d/%02d/%02d', $year, $month, $day);
            $jalali = Jalalian::fromFormat('Y/m/d', $jalaliDateString);
            return $jalali->toCarbon();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function now($format = 'Y/m/d')
    {
        return Jalalian::now()->format($format);
    }

    public static function today($format = 'Y/m/d')
    {
        return Jalalian::now()->format($format);
    }
}

