<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Application\Actions;

use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Infrastructure\Repositories\ProductRepository;

final class DeleteProductAction
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function execute(Product $product): bool
    {
        return $this->repository->delete($product);
    }
}
