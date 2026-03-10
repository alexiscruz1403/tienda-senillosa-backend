<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user;

        $isLiked = false;
        if($user){
            $isLiked = $user->likes()->where('product_id', $this->product_id)->exists();
        }

        return [
            'product_id' => $this->product_id,
            'name' => $this->name,
            'description' => $this->description,
            'brand' => $this->brand,
            'price' => $this->price,
            'category' => $this->category,
            'gender' => $this->gender,
            'discount' => $this->discount_percentage,
            'is_liked' => $isLiked,
            'stocks' => StockResource::collection($this->whenLoaded('stocks')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at,
        ];
    }
}
