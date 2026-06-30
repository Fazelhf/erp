<?php

declare(strict_types=1);

namespace Modules\Dashboard\Application\Queries\GetDashboardStats;

final readonly class GetDashboardStatsQuery
{
    public function __construct(public int $companyId) {}
}
