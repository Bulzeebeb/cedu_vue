<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/xxxx_xx_xx_create_client_accounts_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('client_accounts', function (Blueprint $table) {
    $table->id();
    $table->string('firstName');
    $table->string('middleName')->nullable();
    $table->string('lastName');
    $table->string('extension')->nullable();
    $table->string('gender');
    $table->integer('age');
    $table->string('contactNum');
    $table->string('address');
    $table->string('business_name')->nullable();
    $table->timestamps();
});

    }

    public function down(): void {
        Schema::dropIfExists('client_accounts');
    }
};
