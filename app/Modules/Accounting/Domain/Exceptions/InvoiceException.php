<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Domain\Exceptions;

use RuntimeException;

final class InvoiceException extends RuntimeException
{
    public static function notFound(int $id): self
    {
        return new self("فاکتور با شناسه {$id} یافت نشد.");
    }

    public static function cannotModifyPaid(): self
    {
        return new self('فاکتور پرداخت شده قابل ویرایش نیست.');
    }

    public static function cannotDeletePaid(): self
    {
        return new self('فاکتور پرداخت شده قابل حذف نیست.');
    }
}
