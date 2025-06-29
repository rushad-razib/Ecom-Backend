<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    function rel_to_customer(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
