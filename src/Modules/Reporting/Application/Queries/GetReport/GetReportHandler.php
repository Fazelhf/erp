<?php

declare(strict_types=1);

namespace Modules\Reporting\Application\Queries\GetReport;

use Modules\Reporting\Domain\Report\Entities\Report;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetReportHandler implements QueryHandlerInterface
{
    public function handle(object $query): Report
    {
        /** @var GetReportQuery $query */
        return Report::findOrFail($query->reportId);
    }
}
