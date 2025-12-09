<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('paypark_transactions')) {
            Schema::create('paypark_transactions', function (Blueprint $table) {
                $table->id(); // Transaction ID
                $table->foreignId('client_id')->constrained('pay_park_clients')->onDelete('cascade');
                $table->dateTime('transaction_date');
                $table->decimal('total_payment', 8, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('paypark_transactions');
    }
};
