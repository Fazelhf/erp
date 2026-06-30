<?php

declare(strict_types=1);

namespace Modules\Scheduler\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class RunPayrollJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int    $companyId,
        private readonly string $period,
    ) {}

    public function handle(): void
    {
        // Payroll processing delegated to HRM Payroll bounded context
    }
}
