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
        Schema::create('staff_accounts', function (Blueprint $table) {
    $table->id();
    $table->string('firstName');
    $table->string('middleName')->nullable();
    $table->string('lastName');
    $table->string('extension')->nullable();
    $table->string('gender');
    $table->integer('age');
    $table->string('contactNum');
    $table->string('address');
    $table->string('department')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_accounts');
    }
};
