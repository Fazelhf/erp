<?php

declare(strict_types=1);

namespace Modules\Reporting\Application\Queries\GetReport;

final readonly class GetReportQuery
{
    public function __construct(public int $reportId) {}
}
