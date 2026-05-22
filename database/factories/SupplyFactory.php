<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id'    => Category::factory(),
            'item'           => strtoupper(fake()->words(4, true)),
            'unit_of_measure' => fake()->randomElement(['piece', 'pack', 'set', 'ream', 'box', 'roll']),
            'unit_price'     => fake()->randomFloat(2, 50, 5000),
        ];
    }
}
