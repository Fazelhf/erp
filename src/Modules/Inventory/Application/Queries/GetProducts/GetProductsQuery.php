<?php

declare(strict_types=1);

namespace Modules\Inventory\Application\Queries\GetProducts;

final readonly class GetProductsQuery
{
    public function __construct(
        public int     $companyId,
        public ?string $search  = null,
        public bool    $lowStock = false,
        public int     $perPage = 15,
    ) {}
}
