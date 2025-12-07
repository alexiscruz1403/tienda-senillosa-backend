<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $primaryKey = 'stock_id';

    protected $fillable = [
        'product_id',
        'stock_quantity',
        'size',
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'stock_id', 'stock_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'stock_id', 'stock_id');
    }
}
