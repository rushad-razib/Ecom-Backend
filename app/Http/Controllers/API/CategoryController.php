<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function get_categories(){
        $categories = Category::all();

        return response()->json([
            'categories'=>$categories,
        ]);
    }
}
