<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    function rel_to_customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
