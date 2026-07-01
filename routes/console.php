<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Modules\Reporting\Application\Jobs\PurgeExpiredReportsJob;
use Modules\Scheduler\Application\Jobs\CloseAccountingPeriodJob;
use Modules\Scheduler\Application\Jobs\GenerateDailyReportJob;
use Modules\Scheduler\Application\Jobs\RunPayrollJob;
use Modules\Workflow\Application\Jobs\EscalateWorkflowJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Scheduled Jobs ────────────────────────────────────────────────────────────

// Purge reports older than their expiry date — runs daily at 02:00
Schedule::job(new PurgeExpiredReportsJob)->dailyAt('02:00');

// Escalate stale workflow instances (stuck > 48h) — runs every 6 hours
Schedule::job(new EscalateWorkflowJob)->everySixHours();

// Generate daily summary reports for all active companies — runs at 01:00
Schedule::command('erp:generate-daily-reports')->dailyAt('01:00');
