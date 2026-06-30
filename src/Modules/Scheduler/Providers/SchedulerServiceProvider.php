<?php

declare(strict_types=1);

namespace Modules\Scheduler\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Modules\Scheduler\Application\Jobs\CloseAccountingPeriodJob;
use Modules\Scheduler\Application\Jobs\GenerateDailyReportJob;

class SchedulerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->job(new GenerateDailyReportJob(companyId: 1))
                     ->dailyAt('01:00')
                     ->name('daily-report')
                     ->withoutOverlapping();

            $schedule->job(new CloseAccountingPeriodJob(companyId: 1, period: now()->format('Y-m')))
                     ->monthlyOn(1, '02:00')
                     ->name('close-accounting-period')
                     ->withoutOverlapping();
        });
    }
}
