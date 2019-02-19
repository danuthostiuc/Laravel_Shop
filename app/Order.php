<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'email', 'comment'];

    public function project()
    {
        $this->hasMany(Product::class);
    }

}
