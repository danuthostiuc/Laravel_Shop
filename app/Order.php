<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App
 */
class Order extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'email', 'comment'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
