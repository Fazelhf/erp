<?php

declare(strict_types=1);

namespace Modules\Inventory\Domain\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Domain\Product\Enums\ProductUnitEnum;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'sku',
        'name',
        'description',
        'unit',
        'price',
        'cost_price',
        'stock_quantity',
        'min_stock_level',
        'is_active',
    ];

    protected $casts = [
        'unit'            => ProductUnitEnum::class,
        'price'           => 'decimal:2',
        'cost_price'      => 'decimal:2',
        'stock_quantity'  => 'decimal:2',
        'min_stock_level' => 'decimal:2',
        'is_active'       => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock_level;
    }
}
