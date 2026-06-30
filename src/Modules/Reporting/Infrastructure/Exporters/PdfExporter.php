<?php

declare(strict_types=1);

namespace Modules\Reporting\Infrastructure\Exporters;

use Illuminate\Support\Facades\View;

final class PdfExporter
{
    public function export(string $view, array $data, string $filename): string
    {
        $html = View::make($view, $data)->render();

        // In production: use barryvdh/laravel-dompdf or wkhtmltopdf
        $path = storage_path("app/reports/{$filename}.pdf");

        file_put_contents($path, $html);

        return $path;
    }
}
