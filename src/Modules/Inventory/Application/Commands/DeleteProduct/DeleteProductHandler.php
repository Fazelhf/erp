<?php

declare(strict_types=1);

namespace Modules\Inventory\Application\Commands\DeleteProduct;

use Modules\Inventory\Domain\Product\Entities\Product;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class DeleteProductHandler implements CommandHandlerInterface
{
    public function handle(object $command): bool
    {
        /** @var DeleteProductCommand $command */
        return (bool) Product::findOrFail($command->productId)->delete();
    }
}
