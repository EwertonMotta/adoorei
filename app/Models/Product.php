<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function showPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => number_format($attributes['price'], 2, ',', '.'),
        );
    }

    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity');
    }
}
