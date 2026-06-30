<?php

declare(strict_types=1);

namespace Modules\Reporting\Infrastructure\Exporters;

use Illuminate\Http\Response;

final class ExcelExporter
{
    public function export(array $headers, array $rows, string $filename): Response
    {
        $csv = implode(',', $headers) . "\n";

        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ]);
    }
}
