<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
