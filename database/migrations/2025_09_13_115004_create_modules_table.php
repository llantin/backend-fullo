<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de módulos del sistema.
 *
 * Esta tabla define los diferentes módulos/funcionalidades disponibles
 * en el sistema para control de permisos basado en roles.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'modules' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - name: Nombre del módulo (ej: 'Usuarios', 'Inventario')
     * - route: Ruta única asociada al módulo (para permisos)
     * - icon: Ícono opcional para la interfaz de usuario
     * - timestamps: Campos created_at y updated_at
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('route')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'modules' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
