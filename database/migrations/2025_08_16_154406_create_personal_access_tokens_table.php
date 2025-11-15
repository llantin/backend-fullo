<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear la tabla de tokens de acceso personal (Laravel Sanctum).
 *
 * Esta tabla es utilizada por Laravel Sanctum para gestionar tokens de API
 * que permiten autenticación stateless para aplicaciones SPA y móviles.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea la tabla 'personal_access_tokens' con los siguientes campos:
     * - id: Identificador único autoincremental
     * - tokenable_type: Tipo del modelo al que pertenece el token (morphs)
     * - tokenable_id: ID del modelo al que pertenece el token (morphs)
     * - name: Nombre descriptivo del token
     * - token: Hash único del token (64 caracteres, único)
     * - abilities: Permisos del token en formato JSON (nullable)
     * - last_used_at: Fecha de último uso del token (nullable)
     * - expires_at: Fecha de expiración del token (nullable, indexed)
     * - timestamps: Campos created_at y updated_at
     *
     * La tabla utiliza morphs para relacionarse con cualquier modelo (users, etc.)
     * y permite tokens con permisos específicos y fechas de expiración.
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina la tabla 'personal_access_tokens' si existe.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
