<?php

namespace App\Services;
use App\Models\User;
use App\Models\Product;
use App\Http\Resources\PublicProductsCollection;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LikeService
{
    public function getLikedProducts($user, $page = 1){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $likedProductIds = $userModel->likes()->pluck('product_id')->toArray();

        $products = Product::with(['stocks', 'images'])
            ->whereIn('product_id', $likedProductIds)
            ->paginate(1, ['*'], 'page', $page);

        return new PublicProductsCollection($products);
    }
}
