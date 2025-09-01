<?php

namespace App\Models\PayPark;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PayPark\PayParkClient;

class PayParkTransaction extends Model
{
    use HasFactory;

    
    protected $table = 'paypark_transactions';

    // ✅ Mass-assignable fields
    protected $fillable = [
        'client_id',
        'transaction_date',
        'total_payment',
    ];

    // ✅ Each transaction belongs to one client
   public function client()
{
    return $this->belongsTo(\App\Models\PayPark\PayParkClient::class, 'client_id');
}


}
