<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

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
            $middle = $account->middle_name ? " {$account->middle_name}" : '';
            return "{$account->first_name}{$middle} {$account->last_name}";
        }

        return $this->username;
    }
}
