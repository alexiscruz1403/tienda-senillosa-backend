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
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class, 'address_id', 'address_id');
    }
}
