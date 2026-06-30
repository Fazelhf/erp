<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Enums;

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
            self::Sent      => 'ارسال‌شده',
            self::Paid      => 'پرداخت‌شده',
            self::Cancelled => 'لغوشده',
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
}
