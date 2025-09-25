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
        Schema::create('poultry', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('size');
            $table->string('branch');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('unit');
            $table->string('status')->default('Available');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poultry');
    }
};
