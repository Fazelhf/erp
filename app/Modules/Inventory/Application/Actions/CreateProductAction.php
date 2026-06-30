<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Application\Actions;

use App\Modules\Inventory\Application\DTOs\ProductData;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Infrastructure\Repositories\ProductRepository;

final class CreateProductAction
{
    public function __construct(private readonly ProductRepository $repository) {}

    public function execute(ProductData $data): Product
    {
        return $this->repository->create($data->toArray());
    }
}
