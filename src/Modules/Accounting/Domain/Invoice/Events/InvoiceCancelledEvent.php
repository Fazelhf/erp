<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Events;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;

final class InvoiceCancelledEvent
{
    public function __construct(public readonly Invoice $invoice) {}
}
