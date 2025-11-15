<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de personas.
 *
 * Esta tabla almacena información de personas físicas que pueden ser
 * proveedores, clientes o empleados en el sistema.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'people' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - name: Nombre de la persona
     * - last_name: Apellido de la persona
     * - email: Correo electrónico
     * - phone: Número de teléfono
     * - type: Tipo de persona (proveedor, cliente, empleado)
     * - identification_type: Tipo de identificación (DNI, RUC, etc.)
     * - identification_number: Número de identificación
     * - timestamps: Campos created_at y updated_at
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('type');
            $table->string('identification_type');
            $table->string('identification_number');
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'people' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
