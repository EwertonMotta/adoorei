<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read mixed $id
 * @property-read mixed $name
 * @property-read mixed $showPrice
 * @property-read mixed $pivot
 */
class ProductSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->id,
            'name' => $this->name,
            'price' => $this->showPrice,
            'quantity' => $this->pivot->quantity,
        ];
    }
}
