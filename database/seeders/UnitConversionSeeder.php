<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitConversion;

/**
 * Seeder para poblar la tabla de conversiones de unidades.
 *
 * Define los factores de conversión entre diferentes unidades de medida
 * para permitir cálculos precisos en el sistema de inventario.
 */
class UnitConversionSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de conversiones de unidades.
     *
     * Crea conversiones estándar entre unidades comunes:
     * - Longitud: CM a M (100), etc.
     * - Peso: G a KG (1000), LB a OZ (16)
     * - Volumen: L a GL (3.785), ML a L (1000), etc.
     *
     * Estos factores permiten convertir cantidades entre unidades
     * comerciales y unidades base en el sistema.
     */
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
