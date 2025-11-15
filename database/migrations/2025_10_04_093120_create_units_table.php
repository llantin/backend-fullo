<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de unidades de medida.
 *
 * Esta tabla almacena las diferentes unidades de medida disponibles
 * en el sistema (metros, kilogramos, litros, etc.) para productos.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'units' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - name: Nombre completo de la unidad (ej: 'Metro', 'Kilogramo')
     * - symbol: Símbolo opcional de la unidad (ej: 'm', 'kg')
     * - type: Tipo de unidad (ej: 'longitud', 'peso', 'volumen')
     * - timestamps: Campos created_at y updated_at
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'units' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
