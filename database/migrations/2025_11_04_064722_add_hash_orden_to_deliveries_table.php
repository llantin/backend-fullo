<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para agregar campo hashOrden a la tabla deliveries.
 *
 * Esta migración añade un campo único para identificar órdenes de delivery
 * mediante un hash, utilizado en el sistema de pagos en línea.
 */
return new class extends Migration {
    /**
     * Ejecutar la migración.
     *
     * Agrega el campo 'hashOrden' a la tabla 'deliveries':
     * - hashOrden: Hash único de 64 caracteres para identificar la orden (nullable, unique)
     * - Posicionado después del campo 'observaciones'
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('hashOrden', 64)->unique()->nullable()->after('observaciones');
        });
    }

    /**
     * Revertir la migración.
     *
     * Elimina el campo 'hashOrden' de la tabla 'deliveries'.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('hashOrden');
        });
    }
};
