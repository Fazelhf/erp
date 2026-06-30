<?php

declare(strict_types=1);

namespace Modules\Scheduler\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class CloseAccountingPeriodJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int    $companyId,
        private readonly string $period,
    ) {}

    public function handle(): void
    {
        // Accounting Ledger bounded context handles period closing
    }
}
