<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('parking_settings', function (Blueprint $table) {
        $table->id();
        $table->integer('rate_hour');
        $table->string('rate_minute');
        $table->decimal('rate_fee', 8, 2);
        $table->string('parking_period');
        $table->decimal('overtime_fee', 8, 2)->nullable();
        $table->decimal('overnight_fee', 8, 2)->nullable();
        $table->time('opening_hour');
        $table->time('closing_hour');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_settings');
    }
};
