<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        Item::create([
            "name" => "Varilla de acero corrugado 1/2",
            "description" => "Varilla de acero de alta resistencia utilizada para refuerzo en estructuras de concreto.",
            "brand" => "SiderPerú",
            "model" => "AC-CORR-12",
            "presentation" => "Barra de 9 metros",
            "unit_measurement" => "1",
            "price" => "79.9",
            "category_id" => "1",
        ]);
    }
}
