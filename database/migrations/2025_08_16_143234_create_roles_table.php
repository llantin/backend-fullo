<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de roles de usuario.
 *
 * Esta tabla define los diferentes roles que pueden tener los usuarios
 * en el sistema (ej: administrador, empleado, etc.).
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'roles' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - name: Nombre del rol (ej: 'admin', 'user')
     * - description: Descripción del rol y sus permisos
     * - timestamps: Campos created_at y updated_at
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'roles' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
