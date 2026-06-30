<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;

final class InvoicePaidEvent
{
    use Dispatchable;

    public function __construct(public readonly Invoice $invoice) {}
}
