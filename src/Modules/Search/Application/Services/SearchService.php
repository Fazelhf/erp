<?php

declare(strict_types=1);

namespace Modules\Search\Application\Services;

use Modules\Search\Application\Queries\GlobalSearch\GlobalSearchQuery;
use Shared\Application\Bus\QueryBusInterface;

final class SearchService
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function search(string $query, ?string $index, int $companyId, int $perPage = 20): array
    {
        $indices = $index ? [$index] : ['customers', 'products', 'invoices', 'employees'];

        return $this->queryBus->ask(new GlobalSearchQuery(
            term:      $query,
            companyId: $companyId,
            indices:   $indices,
            limit:     $perPage,
        ));
    }
}
