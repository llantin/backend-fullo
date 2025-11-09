<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('receipt_detail_id');
            $table->decimal('quantity', 10, 2);
            $table->enum('type', ['Compra', 'Venta']);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock');
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
            $table->foreign('receipt_detail_id')->references('id')->on('receipt_details')->onDelete('cascade');

            $table->index(['item_id', 'user_id', 'receipt_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};

