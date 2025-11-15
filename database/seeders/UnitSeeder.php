<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

/**
 * Seeder para poblar la tabla de unidades de medida.
 *
 * Crea el catálogo base de unidades de medida utilizadas en el sistema
 * de inventario, clasificadas por tipo (longitud, peso, volumen, unitario).
 */
class UnitSeeder extends Seeder
{
    /**
     * Ejecutar el seeder de unidades.
     *
     * Crea unidades de medida estándar organizadas por tipo:
     * - Longitud: CM, M, IN
     * - Peso: G, KG, LB
     * - Volumen: GL, L, M3, ML, OZ
     * - Unitario: UND (para artículos contados por unidad)
     *
     * Cada unidad incluye nombre completo, símbolo y tipo de medida.
     */
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
