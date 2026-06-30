<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Domain\Events;

use App\Modules\Accounting\Domain\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class InvoicePaidEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Invoice $invoice) {}
}
