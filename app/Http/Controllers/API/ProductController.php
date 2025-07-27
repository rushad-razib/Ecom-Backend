<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function get_products(){
        $products = Product::with('rel_to_inventory')->get();

        return response()->json([
            'products'=>$products,
        ]);
    }

    function new_arrivals(){
        $new_arrivals = Product::with('first_inventory')->latest()->take(4)->get();

        return response()->json([
            'new_arrivals'=>$new_arrivals,
        ]);
    }
    function all_products(){
        $products = Product::with('rel_to_inventory')->latest()->get();

        return response()->json([
            'products'=>$products,
        ]);
    }
    function get_product_details($id){
        $product_details = Product::with([
            'rel_to_inventory.rel_to_color',
            'rel_to_inventory.rel_to_size',
            'rel_to_gallery',
            ])->find($id);

        $tags = explode(',', $product_details->tag_id);
        $tag_info = Tag::find($tags);


        return response()->json([
            'product_details'=>$product_details,
            'tag_info'=>$tag_info,
        ]);
    }
}