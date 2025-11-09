<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::create([
            'name' => 'Centímetros',
            'symbol' => 'CM',
            'type' => 'Longitud',
        ]);
        Unit::create([
            'name' => 'Galón',
            'symbol' => 'GL',
            'type' => 'Volumen',
        ]);
        Unit::create([
            'name' => 'Gramo',
            'symbol' => 'G',
            'type' => 'Peso',
        ]);
        Unit::create([
            'name' => 'Kilogramo',
            'symbol' => 'KG',
            'type' => 'Peso',
        ]);
        Unit::create([
            'name' => 'Libra',
            'symbol' => 'LB',
            'type' => 'Peso',
        ]);
        Unit::create([
            'name' => 'Litro',
            'symbol' => 'L',
            'type' => 'Volumen',
        ]);
        Unit::create([
            'name' => 'Metro',
            'symbol' => 'M',
            'type' => 'Longitud',
        ]);
        Unit::create([
            'name' => 'Metro cúbico',
            'symbol' => 'M3',
            'type' => 'Volumen',
        ]);
        Unit::create([
            'name' => 'Mililitro',
            'symbol' => 'ML',
            'type' => 'Volumen',
        ]);
        Unit::create([
            'name' => 'Onza',
            'symbol' => 'OZ',
            'type' => 'Volumen',
        ]);
        Unit::create([
            'name' => 'Pulgadas',
            'symbol' => 'IN',
            'type' => 'Longitud',
        ]);
        Unit::create([
            'name' => 'Unidad',
            'symbol' => 'UND',
            'type' => 'Unitario',
        ]);
    }
}
