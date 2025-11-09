<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receipt_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            $table->index(['receipt_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};

