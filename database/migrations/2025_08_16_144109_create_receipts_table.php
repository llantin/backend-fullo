<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de recibos.
 *
 * Esta tabla registra las transacciones principales (compras y ventas)
 * realizadas en el sistema de inventario.
 */
return new class extends Migration {
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'receipts' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - receipt_code: Código único del recibo
     * - description: Descripción opcional del recibo
     * - user_id: ID del usuario que creó el recibo (foreign key)
     * - person_id: ID de la persona (proveedor/cliente) involucrada (foreign key)
     * - type: Tipo de recibo ('Compra' o 'Venta')
     * - timestamps: Campos created_at y updated_at
     *
     * Llaves foráneas:
     * - user_id -> users.id
     * - person_id -> people.id
     *
     * Índice compuesto: (user_id, person_id) para optimizar consultas
     */
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_code')->unique();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('person_id');
            $table->enum('type', ['Compra', 'Venta']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');

            $table->index(['user_id', 'person_id']);
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'receipts' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
