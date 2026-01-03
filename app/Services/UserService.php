<?php

namespace App\Services;
use App\Models\User;
use App\Models\Product;
use App\Http\Resources\PublicProductsResource;

class UserService
{
    public function getLikedProducts($user){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $likedProductIds = $userModel->likes()->pluck('product_id')->toArray();

        $products = Product::with(['stocks', 'images'])
            ->whereIn('product_id', $likedProductIds)
            ->get();

        return PublicProductsResource::collection($products);
    }
}
