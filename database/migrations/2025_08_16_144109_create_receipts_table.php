<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_code')->unique();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('person_id');
            $table->enum('type', ['Compra', 'Venta']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');

            $table->index(['user_id', 'person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
