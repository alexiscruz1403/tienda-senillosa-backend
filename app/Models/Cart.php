<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'stock_id',
        'product_quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'stock_id');
    }

    public function product(){
        return $this->hasOneThrough(
            Product::class,
            Stock::class,
            'stock_id', // Foreign key on Stock table...
            'product_id', // Foreign key on Product table...
            'stock_id', // Local key on Cart table...
            'product_id' // Local key on Stock table...
        );
    }
}
