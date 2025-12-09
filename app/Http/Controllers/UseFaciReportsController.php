<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\UserClient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UseFaciReportsController extends Controller
{
    public function getReportsData(Request $request)
    {
        $categoryId = $request->query('cat');
        $period = $request->query('period', 'daily');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Get all categories for the dropdown
        $categories = Category::select('id', 'name')->get();

        // Build query for bookings
        $query = Booking::with(['facility.category', 'userClient'])
            ->select(
                'bookings.*',
                DB::raw('DATE(bookings.created_at) as date'),
                DB::raw('TIME(bookings.created_at) as time')
            );

        if ($categoryId) {
            $query->whereHas('facility.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('bookings.created_at', [$startDate, $endDate]);
        }

        $bookings = $query->get();

        // Get all facilities count per category for available calculation
        $facilitiesCount = Facility::select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->pluck('count', 'category_id');

        $reportData = [];
        foreach ($bookings as $booking) {
            $bookingDate = $booking->date;
            $facility = $booking->facility;
            $category = $facility ? $facility->category : null;

            if (!$facility || !$category) {
                continue; // Skip bookings with missing facility or category
            }

            $categoryIdForBooking = $category->id;

            // Calculate totals for the category on this date
            $categoryBookingsOnDate = $bookings->filter(function ($b) use ($bookingDate, $categoryIdForBooking) {
                $bFacility = $b->facility;
                $bCategory = $bFacility ? $bFacility->category : null;
                return $b->date == $bookingDate && $bCategory && $bCategory->id == $categoryIdForBooking;
            });

            $totalOccupied = $categoryBookingsOnDate->count();
            $totalBooked = $categoryBookingsOnDate->where('status', 'confirmed')->count();
            $totalAvailable = ($facilitiesCount[$categoryIdForBooking] ?? 0) - $totalOccupied;

            // Calculate total sales for this booking
            $totalSales = $booking->total_amount; // Use the calculated total amount from the model

            $reportData[] = [
                'date' => $booking->date,
                'time' => $booking->time,
                'client' => $booking->userClient ? ($booking->userClient->firstName . ' ' . $booking->userClient->lastName) : 'N/A',
                'name' => $facility->name,
                'cat' => $category->name,
                'occupied' => $totalOccupied,
                'booked' => $totalBooked,
                'available' => max(0, $totalAvailable), // Ensure not negative
                'total_sales' => $totalSales,
                'status' => $booking->status,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $reportData,
            'categories' => $categories
        ]);
    }
}
