<?php

namespace App\Models\PayPark;

use Illuminate\Database\Eloquent\Model;

class ParkingSetting extends Model
{
    protected $fillable = [
        'rate_hour',
        'rate_minute',
        'rate_fee',
        'parking_period',
        'overtime_fee',
        'overnight_fee',
        'opening_hour',
        'closing_hour'
    ];
}
