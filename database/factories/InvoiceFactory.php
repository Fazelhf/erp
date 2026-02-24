<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('fa_IR');
        
        $statuses = ['draft', 'sent', 'paid', 'cancelled'];
        $subtotal = $faker->randomFloat(2, 100000, 10000000);
        $discount = $faker->randomFloat(2, 0, $subtotal * 0.2);
        $tax = ($subtotal - $discount) * 0.09;
        $total = $subtotal - $discount + $tax;
        
        return [
            'invoice_number' => 'INV-' . $faker->unique()->numerify('########'),
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'invoice_date' => $faker->dateTimeBetween('-1 year', 'now'),
            'due_date' => $faker->optional()->dateTimeBetween('now', '+30 days'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'status' => $faker->randomElement($statuses),
            'notes' => $faker->optional()->sentence(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
