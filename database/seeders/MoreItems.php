<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Str;

class MoreItems extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        $unitMeasurements = [
            'CM',
            'GL',
            'G',
            'KG',
            'LB',
            'L',
            'M',
            'M3',
            'ML',
            'OZ',
            'IN',
            'UND'
        ];

        $brands = [
            'Stanley',
            'Bosch',
            'Makita',
            'Dewalt',
            'Truper',
            'Black & Decker',
            'Irwin',
            'Total',
            'Hitachi',
            'Einhell',
            'Craftsman',
            'Sika',
            '3M',
            'Tesa',
            'Philips',
            'Ledvance',
            'Caterpillar',
            'Hilti',
            'Klein Tools',
            'Karcher',
            'Ridgid',
            'Generico'
        ];

        $presentations = [
            'Caja',
            'Blíster',
            'Bolsa',
            'Unidad',
            'Set',
            'Galón',
            'Litro',
            'Paquete',
            'Tambor',
            'Frasco'
        ];

        $categories = Category::pluck('id')->toArray();

        $items = [];

        for ($i = 1; $i <= 500; $i++) {
            $category_id = $faker->randomElement($categories);
            $brand = $faker->randomElement($brands);
            $name = ucfirst($faker->words(rand(2, 4), true));

            $items[] = [
                'name' => $name,
                'description' => 'Producto de ferretería tipo "' . $name . '" ideal para uso profesional o doméstico.',
                'brand' => $brand,
                'model' => strtoupper(Str::random(3)) . '-' . $faker->numberBetween(100, 999),
                'presentation' => $faker->randomElement($presentations),
                'unit_measurement' => $faker->randomElement($unitMeasurements),
                'price' => $faker->randomFloat(2, 1, 1200),
                'minimum_stock' => $faker->numberBetween(5, 20),
                'maximum_stock' => $faker->numberBetween(30, 200),
                'category_id' => $category_id,
                'image' => null, // puedes reemplazar con imagen por defecto si deseas
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Inserta en lotes para mejor rendimiento
        $chunks = array_chunk($items, 100);
        foreach ($chunks as $chunk) {
            Item::insert($chunk);
        }
    }
}
