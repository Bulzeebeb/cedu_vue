<?php

namespace App\Models\PayPark;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayParkBilling extends Model
{
    use HasFactory;

    protected $table = 'parking_settings'; // matches migration table name

    protected $fillable = [
        'rate_hour',     // number of standard hours
        'rate_minute',   // number of standard minutes
        'rate_fee',      // base fee for standard time
        'overtime_fee',  // fee per additional hour
    ];
}
