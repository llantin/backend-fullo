<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de relación entre roles y módulos.
 *
 * Esta tabla establece las relaciones many-to-many entre roles y módulos,
 * permitiendo asignar permisos específicos a cada rol del sistema.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'role_modules' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - role_id: ID del rol (foreign key)
     * - module_id: ID del módulo (foreign key)
     * - timestamps: Campos created_at y updated_at
     *
     * Llaves foráneas:
     * - role_id -> roles.id
     * - module_id -> modules.id
     *
     * Esta tabla permite que un rol tenga acceso a múltiples módulos
     * y que un módulo pueda ser asignado a múltiples roles.
     */
    public function up(): void
    {
        Schema::create('role_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('module_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'role_modules' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_modules');
    }
};
