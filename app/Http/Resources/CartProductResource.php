<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'brand' => $this->brand,
            'name' => $this->name,
            'price' => $this->price,
            'discount' => $this->discount_percentage,
            'images' => ImageResource::collection($this->images),
        ];
    }
}
