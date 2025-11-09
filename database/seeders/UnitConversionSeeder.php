<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitConversion;

class UnitConversionSeeder extends Seeder
{
    public function run(): void
    {
        UnitConversion::create([
            'comercial_unit' => 'CM',
            'base_unit' => 'M',
            'conversion_factor' => 100,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'G',
            'base_unit' => 'KG',
            'conversion_factor' => 1000,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'L',
            'base_unit' => 'GL',
            'conversion_factor' => 3.785,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'L',
            'base_unit' => 'M3',
            'conversion_factor' => 1000,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'LB',
            'base_unit' => 'OZ',
            'conversion_factor' => 16,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'ML',
            'base_unit' => 'GL',
            'conversion_factor' => 3785,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'ML',
            'base_unit' => 'L',
            'conversion_factor' => 1000,
        ]);
        UnitConversion::create([
            'comercial_unit' => 'OZ',
            'base_unit' => 'GL',
            'conversion_factor' => 128,
        ]);
    }
}
