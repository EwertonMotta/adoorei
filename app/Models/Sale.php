<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'amount'
    ];

    public function showAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => number_format($attributes['amount'], 2, ',', '.'),
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public static function generateId()
    {
        $sale = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();
        if ($sale) {
            return $sale->id + 1;
        }
        return (int) today()->format('Ymd') . 1;
    }
}
