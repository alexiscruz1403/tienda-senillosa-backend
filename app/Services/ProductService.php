<?php

namespace App\Services;
use App\Models\Product;
use App\Http\Resources\PublicProductsResource;
use App\Models\Like;

class ProductService
{
    public function getFeaturedProducts($user){
        $products = Product::with(['stocks', 'images'])
            ->get();

        return PublicProductsResource::collection($products);
    }

    public function likeProduct($user, $productId){
        $product = Product::findOrFail($productId);

        if(!$product){
            throw new \Exception("Producto no encontrado");
        }

        $likes = $user->likes();
        if($likes->where('product_id', $productId)->exists()){
            Like::where('user_id', $user->user_id)
                ->where('product_id', $productId)
                ->delete();
            return "Producto eliminado de favoritos";
        }else{
            Like::create([
                'user_id' => $user->user_id,
                'product_id' => $productId
            ]);
            return "Producto añadido a favoritos";
        }
    }

    public function getSingleProduct($productId){
        $product = Product::with(['stocks', 'images'])->findOrFail($productId);

        return new PublicProductsResource($product);
    }

    public function getRelatedProducts($productId){
        $product = Product::findOrFail($productId);

        if(!$product){
            throw new \Exception("Producto no encontrado");
        }

        /**$relatedProducts = Product::with(['stocks', 'images'])
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $productId)
            ->get();
        **/

        $relatedProducts = Product::with(['stocks', 'images'])
            ->get();

        return PublicProductsResource::collection($relatedProducts);
    }
}
