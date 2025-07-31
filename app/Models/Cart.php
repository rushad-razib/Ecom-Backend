<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    function rel_to_product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    function getInventoryAttribute(){
        return Inventory::where('product_id', $this->product_id)
        ->where('color_id', $this->color_id)
        ->where('size_id', $this->size_id)
        ->first();
    }

    protected $guarded = ['id'];
}
