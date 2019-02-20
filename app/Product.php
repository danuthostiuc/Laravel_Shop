<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'description', 'price', 'image'];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
