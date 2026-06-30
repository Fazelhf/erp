<?php

declare(strict_types=1);

namespace Modules\Dashboard\Presentation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Dashboard\Application\Queries\GetDashboardStats\GetDashboardStatsQuery;
use Shared\Application\Bus\QueryBusInterface;

class DashboardController extends Controller
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function __invoke(): View
    {
        $stats = $this->queryBus->ask(new GetDashboardStatsQuery(
            companyId: auth()->user()->company_id,
        ));

        return view('dashboard', compact('stats'));
    }
}
