<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;
    use HasApiTokens, Notifiable;
    protected $fillable = [
        'account_id',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function staffAccount()
    {
        return $this->belongsTo(StaffAccount::class, 'account_id');
    }

    public function clientAccount()
    {
        return $this->belongsTo(ClientAccount::class, 'account_id');
    }

    // Optional accessor for full name
    public function getFullNameAttribute()
    {
        $account = $this->role === 'staff' ? $this->staffAccount : $this->clientAccount;

        if ($account) {
            return "{$account->firstName} {$account->middleName} {$account->lastName}";
        }

        return $this->username;
    }
}
