<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de ítems/productos.
 *
 * Esta tabla almacena la información detallada de cada producto en el inventario,
 * incluyendo datos de identificación, precios, stocks y relación con categorías.
 */
return new class extends Migration {
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'items' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - name: Nombre del producto
     * - description: Descripción detallada del producto
     * - brand: Marca del producto
     * - model: Modelo del producto
     * - presentation: Presentación o empaque
     * - unit_measurement: Unidad de medida (relacionada con units table)
     * - price: Precio del producto (decimal 10,2)
     * - minimum_stock: Stock mínimo para alertas
     * - maximum_stock: Stock máximo recomendado
     * - category_id: ID de la categoría (foreign key)
     * - image: Ruta de la imagen del producto (opcional)
     * - timestamps: Campos created_at y updated_at
     *
     * Llave foránea: category_id -> categories.id (con eliminación en cascada)
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('brand');
            $table->string('model');
            $table->string('presentation');
            $table->string('unit_measurement');
            $table->decimal('price', 10, 2);
            $table->integer('minimum_stock');
            $table->integer('maximum_stock');
            $table->unsignedBigInteger('category_id');
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'items' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
