<?php

declare(strict_types=1);

namespace Modules\Reporting\Application\Commands\GenerateReport;

use Modules\Reporting\Domain\Report\Entities\Report;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class GenerateReportHandler implements CommandHandlerInterface
{
    public function handle(object $command): Report
    {
        /** @var GenerateReportCommand $command */
        return Report::create([
            'company_id'  => $command->companyId,
            'created_by'  => $command->createdBy,
            'type'        => $command->type,
            'name'        => $command->type . '_' . now()->format('Y-m-d_H-i-s'),
            'format'      => $command->format,
            'filters'     => $command->filters,
            'generated_at' => now(),
            'expires_at'  => now()->addDays(7),
        ]);
    }
}
