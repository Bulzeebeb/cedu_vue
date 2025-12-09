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
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['image_url'];

    // Optional: Add accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if image exists in storage first
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
            // Fallback to public images directory
            elseif (file_exists(public_path('images/ProfileImages/' . $this->image))) {
                return asset('images/ProfileImages/' . $this->image);
            }
            // If image file doesn't exist, return default
            else {
                return asset('images/Client/default_profile.png');
            }
        }
        return asset('images/Client/default_profile.png');
    }
}
