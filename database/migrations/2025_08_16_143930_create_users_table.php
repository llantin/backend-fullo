<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para crear las tablas de usuarios y sesiones.
 *
 * Esta migración crea la tabla principal de usuarios y las tablas auxiliares
 * necesarias para el sistema de autenticación de Laravel.
 */
return new class extends Migration
{
    /**
     * Ejecutar la migración.
     *
     * Crea las siguientes tablas:
     *
     * 1. 'users':
     *    - id: Identificador único autoincremental
     *    - person_id: ID de la persona asociada (foreign key)
     *    - username: Nombre de usuario para login
     *    - password: Contraseña hasheada
     *    - role_id: ID del rol del usuario (foreign key)
     *    - remember_token: Token para "recordar sesión"
     *    - timestamps: Campos created_at y updated_at
     *
     * 2. 'password_reset_tokens': Para tokens de reseteo de contraseña
     *    - email: Correo electrónico (primary key)
     *    - token: Token de reseteo
     *    - created_at: Fecha de creación
     *
     * 3. 'sessions': Para manejo de sesiones de usuario
     *    - id: ID de la sesión (primary key)
     *    - user_id: ID del usuario (nullable, indexed)
     *    - ip_address: Dirección IP del usuario
     *    - user_agent: Información del navegador
     *    - payload: Datos serializados de la sesión
     *    - last_activity: Timestamp de última actividad (indexed)
     *
     * Llaves foráneas:
     * - users.person_id -> people.id
     * - users.role_id -> roles.id
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('username');
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina las tablas 'users', 'password_reset_tokens' y 'sessions' si existen.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
