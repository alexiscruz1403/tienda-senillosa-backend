<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id',
        'status',
        'address_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'order_id');
    }
}
