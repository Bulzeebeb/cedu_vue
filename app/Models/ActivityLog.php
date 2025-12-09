<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs'; // Table name in DB

    protected $fillable = [
        'name',          // The name of the user who performed the action
        'role',          // The role of the user (Admin, Staff, etc.)
        'action',        // The action performed
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationship to the user/admin that performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Relationship to an admin (if applicable).
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id')->withDefault();
    }
}
