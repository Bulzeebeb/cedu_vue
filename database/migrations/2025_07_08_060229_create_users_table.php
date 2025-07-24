<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('user_client', function (Blueprint $table) {
            $table->id(); // Primary key: auto-increment
            $table->string('email')->unique(); // Unique email
            $table->string('password');
            $table->string('address');
            $table->unsignedBigInteger('contactNum')->index(); // Indexed for faster search
            $table->string('gender');
            $table->unsignedInteger('age');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('middleName')->nullable();
            $table->string('extension')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_client');
    }
};
