<?php

declare(strict_types=1);

namespace Modules\Inventory\Application\Commands\DeleteProduct;

final readonly class DeleteProductCommand
{
    public function __construct(public int $productId) {}
}
