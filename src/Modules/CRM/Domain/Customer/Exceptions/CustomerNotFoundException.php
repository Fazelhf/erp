<?php

declare(strict_types=1);

namespace Modules\CRM\Domain\Customer\Exceptions;

use RuntimeException;

final class CustomerNotFoundException extends RuntimeException
{
    public static function withId(int $id): self
    {
        return new self("مشتری با شناسه {$id} یافت نشد.");
    }
}
