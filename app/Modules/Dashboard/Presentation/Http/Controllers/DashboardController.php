<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Application\Queries\DashboardStatsQuery;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct(private readonly DashboardStatsQuery $statsQuery) {}

    public function __invoke(): View
    {
        $stats = $this->statsQuery->execute();

        return view('dashboard', $stats);
    }
}
