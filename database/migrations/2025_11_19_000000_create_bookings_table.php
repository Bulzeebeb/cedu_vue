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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_contact');
            $table->dateTime('check_in');
            $table->dateTime('check_out');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('booking_type'); // Hostel, Rental, Commercial
            $table->integer('number_of_guests')->default(1);
            $table->text('additional_notes')->nullable();
            $table->enum('usep_affiliation', ['yes', 'no'])->default('no');
            $table->string('usep_id_file')->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('facility_id');
            $table->index('status');
            $table->index('check_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
