<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

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

            // Get authenticated user (assuming userclient guard for clients)
            $userId = auth('userclient')->id();

            $booking = Booking::create([
                ...$validated,
                'user_id' => $userId,
                'usep_id_file' => $idFilePath,
                'status' => 'Pending'
            ]);

            // Create notifications for all admins about the new booking
            try {
                $facilityName = $booking->facility ? ($booking->facility->name ?? 'facility') : 'facility';
                $admins = Admin::all();
                foreach ($admins as $admin) {
                    Notification::create([
                        'admin_id' => $admin->id,
                        'booking_id' => $booking->id,
                        'title' => 'New Booking Request',
                        'message' => "A new booking for {$facilityName} has been made by {$booking->customer_name}.",
                        'type' => 'new_booking',
                        'read' => false
                    ]);
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the booking creation
                Log::error('Failed to create admin notifications for new booking: ' . $e->getMessage());
            }

            // Return redirect instead of JSON
            return redirect()->back()->with('success', 'Booking submitted successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Booking creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while processing your booking.');
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
                        'user_id' => $booking->user_id,
                        'facility' => $booking->facility->name ?? 'N/A',
                        'category' => $booking->facility->category->name ?? 'N/A',
                        'customer' => $booking->customer_name,
                        'checkIn' => $booking->check_in->format('Y-m-d\TH:i:s'),
                        'checkOut' => $booking->check_out->format('Y-m-d\TH:i:s'),
                        'duration' => $booking->duration,
                        'status' => $booking->status,
                        'email' => $booking->customer_email,
                        'contact' => $booking->customer_contact,
                        'total_amount' => $booking->total_amount ?? 0,
                        'booking_type' => $booking->booking_type,
                        'number_of_guests' => $booking->number_of_guests,
                        'additional_notes' => $booking->additional_notes,
                        'usep_affiliation' => $booking->usep_affiliation,
                        'created_at' => $booking->created_at->format('Y-m-d\TH:i:s'),
                        'special_requests' => $booking->additional_notes, // Map additional_notes to special_requests for frontend
                        'customer_email' => $booking->customer_email,
                        'customer_phone' => $booking->customer_contact,
                        'facility_details' => $booking->facility ? $booking->facility->description : null,
                        'payment_status' => 'Pending' // Default payment status
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
            $booking = Booking::with('facility')->findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|string|in:Pending,Approved,Rejected,Cancelled'
            ]);

            $oldStatus = $booking->status;
            try {
                $booking->update($validated);
            } catch (\Exception $e) {
                Log::error('Failed to update booking: ' . $e->getMessage());
                throw $e;
            }

            // Create notification if status changed
            if ($oldStatus !== $validated['status'] && $booking->user_id) {
                try {
                    $facilityName = $booking->facility ? ($booking->facility->name ?? 'facility') : 'facility';
                    Notification::create([
                        'user_id' => $booking->user_id,
                        'booking_id' => $booking->id,
                        'title' => 'Booking Status Update',
                        'message' => "Your booking for {$facilityName} has been {$validated['status']}.",
                        'type' => 'booking_status',
                        'read' => false
                    ]);
                } catch (\Exception $e) {
                    // Log the error but don't fail the status update
                    Log::error('Failed to create notification for booking status update: ' . $e->getMessage());
                }
            }

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

    /**
     * Update booking details (for editing)
     */
    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::with('facility')->findOrFail($id);

            $validated = $request->validate([
                'check_in' => 'required|date_format:Y-m-d\TH:i',
                'check_out' => 'required|date_format:Y-m-d\TH:i|after:check_in',
                'status' => 'required|string|in:Pending,Approved,Rejected,Cancelled',
                'additional_notes' => 'nullable|string'
            ]);

            $oldStatus = $booking->status;
            $oldCheckIn = $booking->check_in;
            $oldCheckOut = $booking->check_out;

            $booking->update($validated);

            // Recalculate total_amount based on new dates (this will trigger the accessor)
            $booking->save();

            // Create notification for any changes
            if ($booking->user_id) {
                $facilityName = $booking->facility ? $booking->facility->name : 'facility';
                $changes = [];

                if ($oldStatus !== $validated['status']) {
                    $changes[] = "status changed to {$validated['status']}";
                }

                if ($oldCheckIn != $booking->check_in || $oldCheckOut != $booking->check_out) {
                    $changes[] = "dates updated";
                }

                if (!empty($changes)) {
                    $changeText = implode(' and ', $changes);
                    Notification::create([
                        'user_id' => $booking->user_id,
                        'booking_id' => $booking->id,
                        'title' => 'Booking Updated',
                        'message' => "Your booking for {$facilityName} has been updated: {$changeText}.",
                        'type' => 'booking_status',
                        'read' => false
                    ]);
                }
            }

            return response()->json([
                'message' => 'Booking updated successfully',
                'booking' => $booking->load('facility')
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update booking status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'booking_ids' => 'required|array',
                'booking_ids.*' => 'required|integer|exists:bookings,id',
                'status' => 'required|string|in:Pending,Approved,Rejected,Cancelled'
            ]);

            $bookings = Booking::with('facility')->whereIn('id', $validated['booking_ids'])->get();

            $updatedCount = 0;
            foreach ($bookings as $booking) {
                $oldStatus = $booking->status;
                if ($oldStatus !== $validated['status']) {
                    $booking->update(['status' => $validated['status']]);
                    $updatedCount++;

                    // Create notification for each booking
                    if ($booking->user_id) {
                        try {
                            $facilityName = $booking->facility ? $booking->facility->name : 'facility';
                            Notification::create([
                                'user_id' => $booking->user_id,
                                'booking_id' => $booking->id,
                                'title' => 'Booking Status Update',
                                'message' => "Your booking for {$facilityName} has been {$validated['status']}.",
                                'type' => 'booking_status',
                                'read' => false
                            ]);
                        } catch (\Exception $e) {
                            // Log the error but don't fail the bulk update
                            Log::error('Failed to create notification for bulk booking status update: ' . $e->getMessage());
                        }
                    }
                }
            }

            return response()->json([
                'message' => "Bulk status update completed. {$updatedCount} booking(s) updated.",
                'updated_count' => $updatedCount
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error bulk updating bookings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        try {
            $bookings = Booking::with('facility.category')->get();
            
            // Calculate total revenue from approved bookings only
            $totalRevenue = $bookings
                ->where('status', 'Approved')
                ->sum('total_amount');
            
            // Calculate monthly revenue (current month)
            $monthlyRevenue = $bookings
                ->where('status', 'Approved')
                ->filter(function ($booking) {
                    return $booking->check_in->format('Y-m') === now()->format('Y-m');
                })
                ->sum('total_amount');
            
            // Count bookings by type
            $hostellBookings = $bookings->where('booking_type', 'Hostel')->count();
            $rentalBookings = $bookings->where('booking_type', 'Rental')->count();
            $commercialBookings = $bookings->where('booking_type', 'Commercial')->count();
            
            // Count pending bookings
            $pendingBookings = $bookings->where('status', 'Pending')->count();
            
            // Occupancy rates
            $occupancyRates = [
                'overall' => $this->calculateOverallOccupancy($bookings),
                'hostel' => $this->calculateOccupancyByType($bookings, 'Hostel'),
                'rental' => $this->calculateOccupancyByType($bookings, 'Rental'),
                'commercial' => $this->calculateOccupancyByType($bookings, 'Commercial'),
            ];
            
            // Facility counts
            $facilities = Facility::all();
            $hostellFacilities = $facilities->where('category_id', $this->getCategoryId('Hostel'))->count();
            $rentalFacilities = $facilities->where('category_id', $this->getCategoryId('Rental'))->count();
            $commercialFacilities = $facilities->where('category_id', $this->getCategoryId('Commercial'))->count();
            
            return [
                'revenue' => [
                    'total' => (float) $totalRevenue,
                    'monthly' => (float) $monthlyRevenue
                ],
                'total_bookings' => $bookings->count(),
                'pending_bookings' => $pendingBookings,
                'facilities' => $facilities->count(),
                'facilities_active' => $facilities->where('is_active', true)->count(),
                'hostel_rooms' => $hostellBookings,
                'hostel_active' => $hostellFacilities,
                'rental_spaces' => $rentalBookings,
                'rental_active' => $rentalFacilities,
                'commercial_spaces' => $commercialBookings,
                'commercial_active' => $commercialFacilities,
                'occupancy_rates' => $occupancyRates
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating dashboard stats: ' . $e->getMessage());
            return [];
        }
    }

    private function calculateOverallOccupancy($bookings)
    {
        $totalBookings = $bookings->count();
        if ($totalBookings === 0) return 0;
        
        $approvedBookings = $bookings->where('status', 'Approved')->count();
        return round(($approvedBookings / $totalBookings) * 100);
    }

    private function calculateOccupancyByType($bookings, $type)
    {
        $typeBookings = $bookings->where('booking_type', $type);
        $total = $typeBookings->count();
        
        if ($total === 0) return 0;
        
        $approved = $typeBookings->where('status', 'Approved')->count();
        return round(($approved / $total) * 100);
    }

    private function getCategoryId($name)
    {
        $category = \App\Models\Category::where('name', $name)->first();
        return $category ? $category->id : null;
    }
}
