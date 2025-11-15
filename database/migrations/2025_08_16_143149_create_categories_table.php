<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de categorías de productos.
 *
 * Esta tabla almacena las categorías que clasifican los productos/ítems
 * en el sistema de inventario. Cada categoría tiene un nombre y descripción.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'categories' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - name: Nombre de la categoría (string)
     * - description: Descripción de la categoría (string)
     * - timestamps: Campos created_at y updated_at
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'categories' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
