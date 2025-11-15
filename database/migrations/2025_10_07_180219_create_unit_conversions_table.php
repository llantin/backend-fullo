<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de conversiones de unidades.
 *
 * Esta tabla permite definir factores de conversión entre diferentes
 * unidades de medida, facilitando cálculos en el inventario.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'unit_conversions' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - comercial_unit: Unidad comercial (ej: 'Caja', 'Paquete')
     * - base_unit: Unidad base (ej: 'Unidad', 'Kilogramo')
     * - conversion_factor: Factor de conversión (decimal 10,2)
     * - timestamps: Campos created_at y updated_at
     *
     * Ejemplo: Si 1 Caja = 12 Unidades, entonces comercial_unit='Caja',
     * base_unit='Unidad', conversion_factor=12.
     */
    public function up(): void
    {
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('comercial_unit');
            $table->string('base_unit');
            $table->decimal('conversion_factor',10,2);
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'unit_conversions' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_conversions');
    }
};
