<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'statuses';

    protected $primaryKey = 'status_id';

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;
}
