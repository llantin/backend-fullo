<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            "name" => "Articulos de pintura",
            "description" => "Articulos de pintura"
        ]);
        Category::create([
            "name" => "Metales. fierros y aceros",
            "description" => "Metales. fierros y aceros"
        ]);
    }
}
