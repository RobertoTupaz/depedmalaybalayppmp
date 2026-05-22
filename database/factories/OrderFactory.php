<?php

namespace Database\Factories;

use App\Models\Office;
use App\Models\Supply;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'office_id'    => Office::factory(),
            'supply_id'    => Supply::factory(),
            'quantity'     => fake()->numberBetween(1, 50),
            'month_needed' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m'),
        ];
    }
}
