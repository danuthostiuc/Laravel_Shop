<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guard = [];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
