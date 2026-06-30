<?php

declare(strict_types=1);

namespace Modules\Inventory\Application\Commands\CreateProduct;

final readonly class CreateProductCommand
{
    public function __construct(
        public int     $companyId,
        public string  $name,
        public string  $unit,
        public float   $price,
        public ?string $sku           = null,
        public ?string $description   = null,
        public float   $costPrice     = 0.0,
        public float   $stockQuantity = 0.0,
        public float   $minStockLevel = 0.0,
    ) {}
}
