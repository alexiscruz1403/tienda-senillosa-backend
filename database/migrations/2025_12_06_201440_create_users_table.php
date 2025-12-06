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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('username', 255)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('role', 50)->default('cliente');
            $table->string('google_id', 255)->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('address_id')->on('addresses')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
