<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $primaryKey = 'address_id';

    protected $fillable = [
        'province',
        'city',
        'street',
        'postal_code',
        'department',
        'additional_info',
        'user_id',
        'active',
    ];

    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
