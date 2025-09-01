<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs'; // Table name in DB

    protected $fillable = [
        'user_id',       // The ID of the user who performed the action
        'user_type',     // admin, client, etc.
        'target_type',   // what entity was affected (order, product, etc.)
        'target_id',     // optional: ID of the affected entity
        'description',   // short summary of what happened
        'details',       // more details about the action
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
