<?php

declare(strict_types=1);

namespace Modules\Reporting\Domain\Report\Enums;

enum ReportFormatEnum: string
{
    case Pdf   = 'pdf';
    case Excel = 'excel';
    case Csv   = 'csv';
    case Json  = 'json';
}
