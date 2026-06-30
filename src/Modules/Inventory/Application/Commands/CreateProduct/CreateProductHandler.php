<?php

declare(strict_types=1);

namespace Modules\Inventory\Application\Commands\CreateProduct;

use Modules\Inventory\Domain\Product\Entities\Product;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateProductHandler implements CommandHandlerInterface
{
    public function handle(object $command): Product
    {
        /** @var CreateProductCommand $command */
        return Product::create([
            'company_id'      => $command->companyId,
            'sku'             => $command->sku,
            'name'            => $command->name,
            'description'     => $command->description,
            'unit'            => $command->unit,
            'price'           => $command->price,
            'cost_price'      => $command->costPrice,
            'stock_quantity'  => $command->stockQuantity,
            'min_stock_level' => $command->minStockLevel,
            'is_active'       => true,
        ]);
    }
}
