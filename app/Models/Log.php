<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // Specify the correct table name
    protected $table = 'activity_logs';

    // Define the columns that are mass assignable
    protected $fillable = [
        'name',
        'role',
        'action',
        'created_at',
        'updated_at'
    ];

    // Disable automatic timestamps if you're manually inserting created_at / updated_at
    public $timestamps = false;  // Change to true if you want Laravel to handle timestamps
}