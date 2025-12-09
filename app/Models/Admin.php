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
        'raw_password',
        'profile_picture',
        'status',
    ];

    protected $hidden = ['password', 'raw_password'];

    public function getProfilePictureAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function fill(array $attributes)
    {
        return parent::fill($attributes);
    }

    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options);
    }

    public function save(array $options = [])
    {
        return parent::save($options);
    }

}
