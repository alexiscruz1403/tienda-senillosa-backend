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
        Schema::create('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('stock_id');
            $table->integer('product_quantity')->default(1);
            $table->primary(['user_id', 'stock_id']);
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('stock_id')->references('stock_id')->on('stocks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
