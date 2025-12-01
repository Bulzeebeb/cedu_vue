<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Facility
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property array|null $details
 * @property array|null $images
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \App\Models\Category|null $category
 *
 * @mixin \Eloquent
 */
class Facility extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'details',
        'images',
        'is_active',
    ];

    protected $casts = [
        'details' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class);
    }
}
