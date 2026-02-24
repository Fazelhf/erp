<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('fa_IR');
        
        $categories = ['الکترونیک', 'پوشاک', 'غذایی', 'خودرو', 'لوازم خانگی', 'کتاب', 'ورزشی', 'بهداشتی'];
        $units = ['عدد', 'کیلوگرم', 'گرم', 'لیتر', 'متر', 'بسته', 'کارتن'];
        
        $price = $faker->randomFloat(2, 10000, 5000000);
        
        return [
            'name' => $faker->words(3, true),
            'sku' => 'SKU-' . $faker->unique()->numerify('######'),
            'description' => $faker->optional()->paragraph(),
            'price' => $price,
            'stock' => $faker->numberBetween(0, 1000),
            'unit' => $faker->randomElement($units),
            'category' => $faker->randomElement($categories),
            'notes' => $faker->optional()->sentence(),
            'is_active' => $faker->boolean(95),
        ];
    }
}
