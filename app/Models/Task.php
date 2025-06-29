<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = ['id'];
    function rel_to_user(){
        return $this->belongsTo(User::class, 'assigned_to');
    }
    function  rel_to_user2(){
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
