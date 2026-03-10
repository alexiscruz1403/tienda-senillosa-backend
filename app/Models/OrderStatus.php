<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_id',
        'status_id',
        'message'
    ];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function status(){
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }
}
