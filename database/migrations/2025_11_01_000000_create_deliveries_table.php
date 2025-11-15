<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de entregas/delivery.
 *
 * Esta tabla gestiona las entregas de productos, ya sea a domicilio
 * o recogida en tienda, con información de ubicación y estado.
 */
return new class extends Migration {
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'deliveries' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - fk_persona: ID de la persona que recibe la entrega (foreign key)
     * - fk_comprobante: ID del recibo asociado (foreign key)
     * - direccion: Dirección de entrega (nullable)
     * - referencia: Referencia de ubicación (nullable)
     * - latitud: Coordenada latitud (decimal 10,7, nullable)
     * - longitud: Coordenada longitud (decimal 10,7, nullable)
     * - tipo_entrega: Tipo ('delivery' o 'tienda')
     * - estado: Estado de la entrega ('E'=Enviado, 'R'=Recibido, 'C'=Cancelado, default 'E')
     * - fecha_programada: Fecha y hora programada para la entrega (nullable)
     * - observaciones: Notas adicionales (nullable)
     * - timestamps: Campos created_at y updated_at
     *
     * Llaves foráneas:
     * - fk_persona -> people.id
     * - fk_comprobante -> receipts.id
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_persona');
            $table->unsignedBigInteger('fk_comprobante');
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->enum('tipo_entrega', ['delivery', 'tienda']);
            $table->enum('estado', ['E', 'R', 'C'])->default('E');
            $table->dateTime('fecha_programada')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('fk_persona')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('fk_comprobante')->references('id')->on('receipts')->onDelete('cascade');
        });

    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'deliveries' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
