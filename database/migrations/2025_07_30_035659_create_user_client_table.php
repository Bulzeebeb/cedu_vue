<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the table.
     */
    public function up(): void
    {
        Schema::create('user_client', function (Blueprint $table) {
            $table->id();

            // Account credentials
            $table->string('email')->unique();
            $table->string('password');

            // Personal info
            $table->string('firstName', 100);
            $table->string('middleName', 100)->nullable();
            $table->string('lastName', 100);
            $table->string('extension', 10)->nullable();   // e.g. Jr., Sr.
            $table->string('address', 255);
            $table->string('contactNum', 20);
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->unsignedSmallInteger('age');

            // Profile image path (nullable)
            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes(); // 👈 Add this line for deleted_at support
        });
    }

    /**
     * Roll back the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_client');
    }
};