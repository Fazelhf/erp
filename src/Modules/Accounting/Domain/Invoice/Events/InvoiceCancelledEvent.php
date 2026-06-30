<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Events;

use Illuminate\Foundation\Events\Dispatchable;

final class InvoiceCancelledEvent
{
    use Dispatchable;

    public function __construct(public readonly object $invoice) {}
}
