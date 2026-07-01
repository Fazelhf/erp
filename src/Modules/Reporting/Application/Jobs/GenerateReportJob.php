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
use Modules\Reporting\Domain\Report\Enums\ReportFormatEnum;

final class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 2;
    public int $timeout = 300;

    public function __construct(private readonly int $reportId) {}

    public function handle(): void
    {
        $report = Report::find($this->reportId);

        if (! $report || $report->file_path) {
            return;
        }

        $content  = $this->buildContent($report);
        $ext      = $report->format === ReportFormatEnum::Excel ? 'csv' : 'pdf';
        $filename = "reports/{$report->id}_{$report->name}.{$ext}";

        Storage::put($filename, $content);

        $report->update(['file_path' => $filename]);
    }

    private function buildContent(Report $report): string
    {
        // Placeholder — real implementation queries the DB per report type
        return "Report: {$report->name}\nGenerated: " . now()->toDateTimeString();
    }
}
