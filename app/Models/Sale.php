<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
