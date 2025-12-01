<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = Facility::all();

        if ($facilities->isEmpty()) {
            $this->command->warn('No facilities found. Please seed facilities first.');
            return;
        }

        $bookings = [
            [
                'facility_id' => $facilities->first()->id,
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_contact' => '09123456789',
                'check_in' => Carbon::now()->addDays(5),
                'check_out' => Carbon::now()->addDays(8),
                'status' => 'Pending',
                'booking_type' => 'Hostel',
                'number_of_guests' => 2,
                'additional_notes' => 'Need early check-in',
                'usep_affiliation' => 'yes',
            ],
            [
                'facility_id' => $facilities->count() > 1 ? $facilities[1]->id : $facilities->first()->id,
                'customer_name' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'customer_contact' => '09987654321',
                'check_in' => Carbon::now()->addDays(10),
                'check_out' => Carbon::now()->addDays(12),
                'status' => 'Approved',
                'booking_type' => 'Rental',
                'number_of_guests' => 4,
                'additional_notes' => 'Conference setup required',
                'usep_affiliation' => 'no',
            ],
            [
                'facility_id' => $facilities->first()->id,
                'customer_name' => 'Michael Lee',
                'customer_email' => 'michael@example.com',
                'customer_contact' => '09555555555',
                'check_in' => Carbon::now()->addDays(2),
                'check_out' => Carbon::now()->addDays(3),
                'status' => 'Rejected',
                'booking_type' => 'Commercial',
                'number_of_guests' => 1,
                'additional_notes' => '',
                'usep_affiliation' => 'no',
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }

        $this->command->info('Booking seeder completed successfully!');
    }
}
