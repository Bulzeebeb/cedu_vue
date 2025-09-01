<?php

// app/Models/AuditLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'details',
        'ip_address',
        'user_agent'
    ];
}
