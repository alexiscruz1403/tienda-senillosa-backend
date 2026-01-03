<?php

namespace App\Services;
use App\Models\Product;
use App\Http\Resources\PublicProductsResource;
use App\Http\Resources\PublicProductsCollection;
use App\Models\Like;
use App\Models\OrderProduct;
use App\Models\User;

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

    public function getManyProducts($search = null, $categories = null, $gender = null, $page = 1, $ordering = null){
        $products = Product::with(['stocks', 'images'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('brand', 'LIKE', "%{$search}%");
                });
            })
            ->when($categories, function ($query, $categories) {
                $query->whereIn('category', $categories);
            })
            ->when($gender, function($query, $gender){
                $query->where('gender', $gender);
            })
            ->when($ordering, function($query, $ordering){
                switch($ordering){
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'best_selling':
                        $query->withCount('orderProducts')
                            ->orderBy('order_products_count', 'desc');
                        break;
                    default:
                        break;
                }
            })
            ->paginate(1, ['*'], 'page', $page);

        return new PublicProductsCollection($products);
    }
}
