<?php

declare(strict_types=1);

namespace Modules\Inventory\Domain\Product\Events;

use Modules\Inventory\Domain\Product\Entities\Product;

final class LowStockDetectedEvent
{
    public function __construct(public readonly Product $product) {}
}
