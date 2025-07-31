<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $fillable = [
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'address',
        'contact',
        'gender',
        'age',
        'role',
        'password',
    ];

    protected $hidden = ['password'];
}