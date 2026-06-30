<?php

declare(strict_types=1);

namespace App\Modules\CRM\Domain\Exceptions;

use App\Modules\Core\Domain\Exceptions\CoreException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CustomerNotFoundException extends NotFoundHttpException
{
    public static function forId(int $id): self
    {
        return new self("مشتری با شناسه {$id} یافت نشد.");
    }
}
