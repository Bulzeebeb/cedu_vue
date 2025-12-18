<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Booking extends Model
{
    protected $fillable = [
        'facility_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_contact',
        'check_in',
        'check_out',
        'status',
        'booking_type',
        'number_of_guests',
        'total_amount',
        'additional_notes',
        'usep_affiliation',
        'usep_id_file'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userClient(): BelongsTo
    {
        return $this->belongsTo(UserClient::class, 'user_id');
    }

    public function getDurationAttribute()
    {
        if ($this->check_in && $this->check_out) {
            // Calculate duration in days, rounding up for partial days
            $diffInHours = $this->check_in->diffInHours($this->check_out, false);
            return ceil($diffInHours / 24); // Round up to next day for partial bookings
        }
        return 0;
    }

    public function getTotalAmountAttribute()
    {
        try {
            if ($this->facility && $this->check_in && $this->check_out) {
                $duration = $this->duration;
                $facilityDetails = $this->facility->details;

                if (is_array($facilityDetails) || is_object($facilityDetails)) {
                    // Extract price from facility details JSON
                    $priceRate = $facilityDetails['Price/Rate'] ?? '₱0.00';

                    // Remove currency symbol and convert to float
                    $priceNumeric = (float) str_replace(['₱', ','], '', $priceRate);

                    return $duration * $priceNumeric;
                }
            }
        } catch (\Exception $e) {
            // Log the error and return 0
            Log::error('Error calculating total amount for booking ' . $this->id . ': ' . $e->getMessage());
        }
        return 0;
    }
}
