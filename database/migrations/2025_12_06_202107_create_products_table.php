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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('brand', 100)->default('Marca Genérica');
            $table->decimal('price', 10, 2);
            $table->string('category', 100)->default('Remeras');
            $table->string('gender', 100)->default('Unisex');
            $table->string('image_url', 255)->nullable();
            $table->integer('discount_percentage')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
