<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchFilterController extends Controller
{
    function get_categories(){
        $categories = Category::all();

        return response()->json([
            'categories'=>$categories,
        ]);
    }
    function get_colors(){
        $colors = Color::all();

        return response()->json([
            'colors'=>$colors,
        ]);
    }
    function search(Request $request){
        $category_id = $request->input('category_id', []);
        $color_id = $request->input('color_id', []);
        $minPrice = (int) $request->input('minPrice', 0);
        $maxPrice = (int) $request->input('maxPrice', 100000);

        $query = Product::query();

        if(!empty($category_id)){
            $query->whereIn('category_id', $category_id);
        }
        if(!empty($color_id) || $minPrice > 0 || $maxPrice < 500000){
            $query->whereHas('rel_to_inventory', function($q) use ($color_id, $minPrice, $maxPrice){
                $q->whereBetween('after_discount', [$minPrice, $maxPrice]);
                if(!empty($color_id)){
                    $q->whereIn('color_id', $color_id);
                }
            });
        }

        $products = $query->with('rel_to_inventory', 'first_inventory')->get();
        return response()->json([
            'products'=>$products,
        ]);

    }
    
}
