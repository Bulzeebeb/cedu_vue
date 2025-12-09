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
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the existing foreign key constraint that references 'users'
            $table->dropForeign(['user_id']);
            // Add new foreign key constraint that references 'user_client'
            $table->foreign('user_id')->references('id')->on('user_client')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the foreign key constraint to 'user_client'
            $table->dropForeignKey(['user_id']);
            // Re-add the original foreign key constraint to 'users'
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
