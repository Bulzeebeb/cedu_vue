<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Booking;
use App\Models\Facility;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Calculate and update total_amount for all bookings based on facility pricing
        $bookings = Booking::all();
        
        foreach ($bookings as $booking) {
            $totalAmount = $booking->total_amount; // This will use the accessor
            
            // Update the database column with the calculated value
            Booking::where('id', $booking->id)->update([
                'total_amount' => $totalAmount
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset to 0
        Booking::query()->update(['total_amount' => 0]);
    }
};
