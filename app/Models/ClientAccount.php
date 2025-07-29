<?php

// app/Models/ClientAccount.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'extension',
        'gender',
        'age',
        'contactNum',
        'address',
        'business_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'account_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstName} {$this->middleName} {$this->lastName}";
    }
}

