<?php

declare(strict_types=1);

namespace Modules\Inventory\Application\Queries\GetProducts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Inventory\Domain\Product\Entities\Product;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetProductsHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetProductsQuery $query */
        return Product::query()
            ->where('company_id', $query->companyId)
            ->when($query->search, fn ($q, $s) => $q->where(fn ($q) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
            ))
            ->when($query->lowStock, fn ($q) => $q->whereColumn('stock_quantity', '<=', 'min_stock_level'))
            ->latest()
            ->paginate($query->perPage);
    }
}
