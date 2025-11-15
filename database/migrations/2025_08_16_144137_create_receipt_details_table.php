<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de detalles de recibos.
 *
 * Esta tabla almacena los ítems específicos incluidos en cada recibo,
 * con cantidades, precios y subtotales.
 */
return new class extends Migration {
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'receipt_details' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - receipt_id: ID del recibo al que pertenece (foreign key)
     * - item_id: ID del ítem/producto (foreign key)
     * - quantity: Cantidad del ítem en el recibo (decimal 10,2)
     * - unit: Unidad de medida (opcional, string 50)
     * - price: Precio unitario del ítem (decimal 10,2)
     * - subtotal: Subtotal (cantidad * precio, decimal 12,2)
     * - timestamps: Campos created_at y updated_at
     *
     * Llaves foráneas:
     * - receipt_id -> receipts.id
     * - item_id -> items.id
     *
     * Índice compuesto: (receipt_id, item_id) para optimizar consultas
     */
    public function up(): void
    {
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->index(['receipt_id', 'item_id']);
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'receipt_details' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};

