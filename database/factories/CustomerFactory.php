<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('fa_IR');
        
        return [
            'name' => $faker->name(),
            'company_name' => $faker->optional()->company(),
            'email' => $faker->optional()->email(),
            'phone' => $faker->optional()->phoneNumber(),
            'mobile' => '09' . $faker->numerify('#########'),
            'address' => $faker->optional()->address(),
            'national_id' => $faker->optional()->numerify('##########'),
            'economic_code' => $faker->optional()->numerify('##########'),
            'notes' => $faker->optional()->sentence(),
            'is_active' => $faker->boolean(90),
        ];
    }
}
