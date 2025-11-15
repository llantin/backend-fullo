<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de movimientos de inventario (Kardex).
 *
 * Esta tabla registra todos los movimientos de entrada y salida de productos,
 * manteniendo un historial completo del inventario (kardex).
 */
return new class extends Migration {
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'movements' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - item_id: ID del ítem/producto (foreign key)
     * - user_id: ID del usuario que realizó el movimiento (foreign key)
     * - receipt_id: ID del recibo asociado (foreign key)
     * - receipt_detail_id: ID del detalle del recibo (foreign key)
     * - quantity: Cantidad del movimiento (decimal 10,2)
     * - type: Tipo de movimiento ('Compra' o 'Venta')
     * - price: Precio unitario del movimiento (decimal 10,2, default 0)
     * - stock: Stock resultante después del movimiento
     * - timestamps: Campos created_at y updated_at
     *
     * Llaves foráneas:
     * - item_id -> items.id
     * - user_id -> users.id
     * - receipt_id -> receipts.id
     * - receipt_detail_id -> receipt_details.id
     *
     * Índice compuesto: (item_id, user_id, receipt_id) para optimizar consultas
     */
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('receipt_detail_id');
            $table->decimal('quantity', 10, 2);
            $table->enum('type', ['Compra', 'Venta']);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
            $table->foreign('receipt_detail_id')->references('id')->on('receipt_details')->onDelete('cascade');

            $table->index(['item_id', 'user_id', 'receipt_id']);
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'movements' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};

