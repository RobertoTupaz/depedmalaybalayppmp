<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            OfficeSeeder::class,
            SupplySeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ppmp.local',
            'password' => bcrypt('password'),
        ]);
    }
}