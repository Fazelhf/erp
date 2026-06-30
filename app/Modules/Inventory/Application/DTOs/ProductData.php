<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Application\DTOs;

final readonly class ProductData
{
    public function __construct(
        public string  $name,
        public ?string $sku,
        public ?string $description,
        public float   $price,
        public int     $stock,
        public string  $unit,
        public ?string $category,
        public ?string $notes,
        public bool    $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name:        $data['name'],
            sku:         $data['sku'] ?? null,
            description: $data['description'] ?? null,
            price:       (float) $data['price'],
            stock:       (int) $data['stock'],
            unit:        $data['unit'],
            category:    $data['category'] ?? null,
            notes:       $data['notes'] ?? null,
            isActive:    (bool) ($data['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'sku'         => $this->sku,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'unit'        => $this->unit,
            'category'    => $this->category,
            'notes'       => $this->notes,
            'is_active'   => $this->isActive,
        ];
    }
}
