<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->integer('age')->nullable();

            $table->string('email')->unique();
            $table->string('password');

            $table->enum('role', ['Admin', 'Staff'])->default('Staff');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};