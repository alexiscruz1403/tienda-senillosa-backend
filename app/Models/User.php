<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'google_id',
        'phone_number',
        'address_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'google_id',
        'role',
    ];

    protected $attributes = [
        'role' => 'cliente'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id', 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}
