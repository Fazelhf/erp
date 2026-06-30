<?php

declare(strict_types=1);

namespace Modules\Inventory\Domain\Product\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\Inventory\Domain\Product\Entities\Product;

final class LowStockDetectedEvent
{
    use Dispatchable;

    public function __construct(public readonly Product $product) {}
}
