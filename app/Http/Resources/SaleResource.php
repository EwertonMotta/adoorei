<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read mixed $id
 * @property-read mixed $showAmount
 * @property-read mixed $products
 */
class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'sale_id' => $this->id,
            'amount' => $this->showAmount,
            'products' => ProductSaleResource::collection($this->products),
        ];
    }
}
