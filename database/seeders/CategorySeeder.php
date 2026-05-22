<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ["name" => "Common Electrical Supplies", "description" => null],
            ["name" => "Common Computer Supplies", "description" => null],
            ["name" => "Common Office Supplies", "description" => null],
            ["name" => "Common Office Devices", "description" => null],
            ["name" => "Common Janitorial Supplies", "description" => null],
            ["name" => "Legal Size Paper", "description" => null],
            ["name" => "Common Office Equipment", "description" => null],
            ["name" => "Medical Supplies", "description" => null],
            ["name" => "Seminars and Trainings", "description" => null],
            ["name" => "Tarpaulin With Layout", "description" => null],
            ["name" => "Tarpaulin Without Layout", "description" => null],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}