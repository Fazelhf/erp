<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@erp.local')->exists()) {
            User::factory()->create([
                'name' => 'مدیر سیستم',
                'email' => 'admin@erp.local',
            ]);
        }

        if (User::count() < 6) {
            User::factory()->count(5)->create();
        }

        $this->call([
            CustomerSeeder::class,
            ProductSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
