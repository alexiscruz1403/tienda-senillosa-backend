<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product' => new PublicProductsResource($this->whenLoaded('product')),
            'size' => $this->product_size,
            'quantity' => $this->product_quantity,
            'price' => $this->product_price,
        ];
    }
}
