<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'stock' => new StockResource($this->whenLoaded('stock')),
            'product' => new CartProductResource($this->whenLoaded('product')),
            'product_quantity' => $this->product_quantity,
        ];
    }
}
