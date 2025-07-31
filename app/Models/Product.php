<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'branch', 'category', 'price', 'stock', 'unit', 'status', 'image'
    ];
}

