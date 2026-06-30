<?php

declare(strict_types=1);

namespace Modules\Reporting\Application\Commands\GenerateReport;

final readonly class GenerateReportCommand
{
    public function __construct(
        public int    $companyId,
        public int    $createdBy,
        public string $type,
        public string $format,
        public array  $filters = [],
    ) {}
}
