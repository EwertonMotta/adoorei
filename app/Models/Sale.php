<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'amount',
    ];

    public function showAmount(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, mixed $attributes) => number_format($attributes['amount'], 2, ',', '.'),
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public static function generateId(): int|string
    {
        $sale = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();
        if ($sale) {
            return (string) $sale->id + 1;
        }

        return today()->format('Ymd'). 1;
    }
}
