<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('qty');
            $table->enum('size', ['S','M','L','XL','XXL','XXXL']);
            $table->enum('status', ['Menunggu_Pembayaran', 'Dikemas', 'Dikirim', 'Selesai']);
            $table->string('address');
            $table->integer('total_price');
            $table->date('date_order');
            $table->date('estimate_arrived');
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
