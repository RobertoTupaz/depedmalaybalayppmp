<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                    => fake()->company() . ' Office',
            'group'                   => 'SGOD',
            'allocation'              => fake()->numberBetween(10000, 30000),
            'prepared_by'             => fake()->name(),
            'prepared_by_designation' => fake()->jobTitle(),
            'reviewed_by'             => fake()->name(),
            'reviewed_by_designation' => fake()->jobTitle(),
            'approved_by'             => fake()->name(),
            'approved_by_designation' => fake()->jobTitle(),
        ];
    }
}
