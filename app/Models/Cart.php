<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id'];
    function rel_to_product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}
