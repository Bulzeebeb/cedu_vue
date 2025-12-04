<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function getDurationAttribute()
    {
        if ($this->check_in && $this->check_out) {
            return $this->check_out->diffInDays($this->check_in);
        }
        return 0;
    }
}
