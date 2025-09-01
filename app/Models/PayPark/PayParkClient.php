<?php

namespace App\Models\PayPark;

use Illuminate\Database\Eloquent\Model;

class PayParkClient extends Model
{
    protected $table = 'pay_park_clients';

    protected $fillable = [
        'name',
        'plate',
        'time_in',
        'time_out',
        'status',
        'qr_code',
    ];

    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];
    public function transactions()
{
    return $this->hasMany(PayParkTransaction::class);
}

}

