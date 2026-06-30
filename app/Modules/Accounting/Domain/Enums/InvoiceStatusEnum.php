<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Domain\Enums;

enum InvoiceStatusEnum: string
{
    case Draft     = 'draft';
    case Sent      = 'sent';
    case Paid      = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'پیش‌نویس',
            self::Sent      => 'ارسال شده',
            self::Paid      => 'پرداخت شده',
            self::Cancelled => 'لغو شده',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft     => 'secondary',
            self::Sent      => 'primary',
            self::Paid      => 'success',
            self::Cancelled => 'danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
