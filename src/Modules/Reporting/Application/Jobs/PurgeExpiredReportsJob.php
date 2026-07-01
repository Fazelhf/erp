<?php

declare(strict_types=1);

namespace Modules\Reporting\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Modules\Reporting\Domain\Report\Entities\Report;

final class PurgeExpiredReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function handle(): void
    {
        Report::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->each(function (Report $report): void {
                if ($report->file_path && Storage::exists($report->file_path)) {
                    Storage::delete($report->file_path);
                }
                $report->delete();
            });
    }
}
