<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\Stock;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'category',
        'genre',
        'image_url',
        'discount_percentage',
    ];

    protected $casts = [
        'price' => 'float',
        'discount_percentage' => 'integer',
    ];

    protected $attributes = [
        'discount_percentage' => 0,
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'product_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'product_id', 'product_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id', 'product_id');
    }

    public function orderProducts()
    {
        return $this->hasManyThrough(
            OrderProduct::class,  // Modelo final
            Stock::class,         // Modelo intermedio
            'product_id',         // Foreign key en stocks (intermedio)
            'stock_id',           // Foreign key en order_products (final)
            'product_id',         // Local key en products
            'stock_id'            // Local key en stocks (intermedio)
        );
    }
}
