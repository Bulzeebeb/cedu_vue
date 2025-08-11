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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('contact', 20);
            $table->date('order_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Fix: Reference the correct table name 'user_client' instead of 'userclients'
            $table->foreign('user_id')->references('id')->on('user_client')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index('order_date');
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