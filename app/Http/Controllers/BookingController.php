<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'facility_id' => 'required|exists:facilities,id',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_contact' => 'required|string|max:20',
                'check_in' => 'required|date_format:Y-m-d\TH:i',
                'check_out' => 'required|date_format:Y-m-d\TH:i',
                'booking_type' => 'required|string',
                'number_of_guests' => 'required|integer|min:1',
                'additional_notes' => 'nullable|string',
                'usep_affiliation' => 'required|string|in:yes,no',
                'usep_id_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);

            $idFilePath = null;
            if ($request->hasFile('usep_id_file')) {
                $idFilePath = $request->file('usep_id_file')->store('usep_ids', 'public');
            }

            $booking = Booking::create([
                ...$validated,
                'usep_id_file' => $idFilePath,
                'status' => 'Pending'
            ]);

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all bookings for admin
     */
    public function index()
    {
        try {
            $bookings = Booking::with('facility.category')
                ->orderBy('check_in', 'desc')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'facility' => $booking->facility->name ?? 'N/A',
                        'category' => $booking->facility->category->name ?? 'N/A',
                        'customer' => $booking->customer_name,
                        'checkIn' => $booking->check_in->format('Y-m-d'),
                        'checkOut' => $booking->check_out->format('Y-m-d'),
                        'duration' => $booking->duration,
                        'status' => $booking->status,
                        'email' => $booking->customer_email,
                        'contact' => $booking->customer_contact,
                    ];
                });

            return response()->json($bookings);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching bookings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|string|in:Pending,Approved,Rejected'
            ]);

            $booking->update($validated);

            return response()->json([
                'message' => 'Booking status updated',
                'booking' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
