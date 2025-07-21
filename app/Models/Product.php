<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    function rel_to_category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    function rel_to_subcategory(){
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }
    function rel_to_inventory(){
        return $this->hasMany(Inventory::class);
    }
    function first_inventory(){
        return $this->hasOne(Inventory::class, 'product_id')->oldest();
    }
    function rel_to_gallery(){
        return $this->hasMany(Gallery::class, 'product_id');
    }
}
