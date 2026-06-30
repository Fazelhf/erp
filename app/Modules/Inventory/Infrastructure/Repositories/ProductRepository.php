<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Infrastructure\Repositories;

use App\Modules\Core\Infrastructure\Repositories\BaseRepository;
use App\Modules\Inventory\Domain\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ProductRepository extends BaseRepository
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function paginateWithSearch(?string $search, bool $lowStockOnly = false, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($lowStockOnly) {
            $query->where('stock', '<', 10);
        }

        return $query->latest()->paginate($perPage);
    }

    public function allActive(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->newQuery()->active()->get();
    }
}
