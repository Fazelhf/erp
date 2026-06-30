<?php

declare(strict_types=1);

namespace Modules\Search\Application\Queries\GlobalSearch;

final readonly class GlobalSearchQuery
{
    public function __construct(
        public string $term,
        public int    $companyId,
        public array  $indices = ['customers', 'products', 'invoices', 'users'],
        public int    $limit   = 20,
    ) {}
}
