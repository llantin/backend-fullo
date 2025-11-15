<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración estándar de Laravel para crear tablas de caché.
 *
 * Crea las tablas necesarias para el sistema de caché de Laravel:
 * - 'cache': Almacena datos cacheados con clave, valor y expiración
 * - 'cache_locks': Maneja bloqueos para evitar race conditions en caché
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea las tablas 'cache' y 'cache_locks' para el sistema de caché.
     */
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina las tablas 'cache' y 'cache_locks'.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
