<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // For login/auth
use Illuminate\Database\Eloquent\SoftDeletes; // For soft deletes
use Illuminate\Support\Facades\Hash;

class UserClient extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'user_client'; // Custom table name

    protected $fillable = [
        'email',
        'password',
        'address',
        'contactNum',
        'gender',
        'age',
        'firstName',
        'lastName',
        'middleName',
        'extension',
        'image',
    ];

    public $timestamps = true;

}
