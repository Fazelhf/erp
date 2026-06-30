<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Exceptions;

use RuntimeException;

final class InvoiceException extends RuntimeException
{
    public static function notFound(int $id): self
    {
        return new self("فاکتور با شناسه {$id} یافت نشد.");
    }

    public static function cannotModifyPaid(): self
    {
        return new self('فاکتور پرداخت‌شده قابل ویرایش نیست.');
    }

    public static function cannotDeletePaid(): self
    {
        return new self('فاکتور پرداخت‌شده قابل حذف نیست.');
    }
}
