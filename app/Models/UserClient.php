<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserClient extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'user_client';

    protected $fillable = [
        'email',
        'password',
        'firstName',
        'middleName',
        'lastName',
        'extension',
        'address',
        'contactNum',
        'gender',
        'age',
        'image', // ✅ Make sure this is included
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Optional: Add accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/Client/default_profile.png');
    }
}
