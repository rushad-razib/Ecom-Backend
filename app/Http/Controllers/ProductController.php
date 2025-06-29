<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Gallery;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    function product_view(){
        $tags = Tag::all();
        $categories = Category::all();
        $products = Product::all();
        $inventories = Inventory::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        $products_trashed = Product::onlyTrashed()->get();
        return view('backend.product.product_view', [
            'tags'=>$tags,
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'products'=>$products,
            'brands'=>$brands,
            'inventories'=>$inventories,
            'products_trashed'=>$products_trashed,
        ]);
    }
    function product_store_view(){
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $tags = Tag::all();
        $brands = Brand::all();
        return view('backend.product.product_store_view', [
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'tags'=>$tags,
            'brands'=>$brands,
        ]);
    }
    function getSubcategory(Request $request){
        $str = '<option>Select Subcategory</option>';
        $subcat = Subcategory::where('category_id', $request->category_id)->get();
        foreach($subcat as $sub){
            $str .= "<option value=".$sub->id.">".$sub->name."</option>";
        }
        return $str;
    }
    function product_store(Request $request){
        $request->validate([
            'name'=>['required', 'unique:products'],
            'price'=>'required',
            'category'=>'required',
            'subcategory'=>'required',
            'desp'=>'required',
            'image'=>['required', 'mimes:jpg,png,webp'],
        ], [
            'desp.required'=>'Please provide product description'
        ]);

        $image = $request->image;
        $extension = $image->extension();
        $filename = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);
        $image->scale(300, 300);
        $image->save(public_path('uploads/product/').$filename);
        $tags = is_array($request->tags) ? implode(',', $request->tags) : '';


        $product_id = Product::insertGetId([
            'name'=>mb_convert_case($request->name, MB_CASE_TITLE, 'utf-8'),
            'discount'=>$request->discount,
            'price'=>$request->price,
            'after_discount'=>$request->price-($request->price * $request->discount/100),
            'category_id'=>$request->category,
            'subcategory_id'=>$request->subcategory,
            'tag_id'=>$tags,
            'brand_id'=>$request->brand,
            'description'=>$request->desp,
            'image'=>$filename,
            'slug'=>Str::slug($request->name) . '-' . random_int(4000, 5000),
            'created_at'=>Carbon::now(),
        ]);

        foreach ($request->gal_image as $gal) {
            $extension = $gal->extension();
            $filename = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($gal);
            $image->scale(width:700);
            $image->save(public_path('uploads/gallery/').$filename);
            Gallery::insert([
                'product_id'=>$product_id,
                'image'=>$filename,
                'created_at'=>Carbon::now(),
            ]);
        }

        return back()->with('success', 'Product added successfully');
    }
    function product_delete($id){
        Product::find($id)->delete();
        Inventory::where('product_id', $id)->delete();
        return back()->with('success', 'Product moved to trash');
    }
    function product_restore($id){
        Product::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Product restored');
    }
    function product_trash_delete($id){
        Product::onlyTrashed()->find($id)->forceDelete();
        return back()->with('success', 'Product removed');
    }
    function product_edit($id){
        $product = Product::find($id);
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $tags = Tag::all();
        return view('backend.product.product_edit', [
            'product'=>$product,
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'tags'=>$tags,
        ]);
    }
    function product_update(Request $request, $id){
        $product = Product::find($id);
        $tags = is_array($request->tags) ? implode(',', $request->tags) : '';
        if($request->image){
            $del_from = public_path('uploads/product/').$product->image;
            unlink($del_from);

            $image = $request->image;
            $extension = $image->extension();
            $filename = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->scale(300, 300);
            $image->save(public_path('uploads/product/').$filename);

            Product::find($id)->update([
                'name'=>$request->name,
                'price'=>$request->price,
                'discount'=>$request->discount,
                'after_discount'=>$request->price - ($request->price * $request->discount/100),
                'category_id'=>$request->category,
                'subcategory_id'=>$request->subcategory,
                'tag_id'=>$tags,
                'description'=>$request->desp,
                'image'=>$filename,
            ]);
        }
        else{
            Product::find($id)->update([
                'name'=>$request->name,
                'price'=>$request->price,
                'discount'=>$request->discount,
                'after_discount'=>$request->price - ($request->price * $request->discount/100),
                'category_id'=>$request->category,
                'subcategory_id'=>$request->subcategory,
                'tag_id'=>$tags,
                'description'=>$request->desp,
            ]);
        }
        if($request->gal_image){
            foreach($request->gal_image as $img){
                $image = $img;
                $extension = $image->extension();
                $filename = uniqid().'.'.$extension;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image);
                $image->scale(300, 300);
                $image->save(public_path('uploads/gallery/').$filename);

                Gallery::insert([
                    'product_id'=>$id,
                    'image'=>$filename,
                ]);
            }
        }
        return redirect()->route('product.view');
        
    }
    function product_variation(){
        $colors = Color::all();
        $sizes = Size::all();
        return view('backend.variation.variation_view', [
            'colors'=>$colors,
            'sizes'=>$sizes,
        ]);
    }
    function color_store(Request $request){
        Color::insert([
            'name'=>$request->name,
            'code'=>$request->code,
            'created_at'=>Carbon::now(),
        ]);
        return back()->with('success', 'Color added successfully');
    }
    function size_store(Request $request){
        Size::insert([
            'name'=>$request->name,
            'created_at'=>Carbon::now(),
        ]);
        return back()->with('success', 'Size added successfully');
    }
    function size_del($id){
        Size::find($id)->delete();
        return back()->with('success', 'Size removed');
    }
    function color_del($id){
        Color::find($id)->delete();
        return back()->with('success', 'Color removed');
    }
    function product_inventory($id){
        $inventories = Inventory::where('product_id', $id)->get();
        $product_info = Product::find($id);
        $colors = Color::all();
        $sizes = Size::all();
        return view('backend.inventory.inventory', [
            'product_info'=>$product_info,
            'colors'=>$colors,
            'sizes'=>$sizes,
            'inventories'=>$inventories,
        ]);
    }
    function inventory_store(Request $request, $id){
        if (Inventory::where('product_id', $id)->where('color_id', $request->color)->where('size_id', $request->size)->exists()) {
            Inventory::where('product_id', $id)->where('color_id', $request->color)->where('size_id', $request->size)->increment('quantity', $request->quantity);
        }
        else{
            $product = Product::find($id);
            $color = Color::find($request->color);
            $size = Size::find($request->size);
            $sku = Str::upper(Str::slug(implode('-', array_slice(explode(' ', $product->name), 0, 2)), '-')).'-'.Str::upper(substr($color->name, 0, 2)).'-'.Str::upper(Str::slug(implode('-', array_slice(explode(' ', $size->name), 0, 2)), '-'));

            Inventory::insert([
                'product_id'=>$id,
                'color_id'=>$request->color,
                'size_id'=>$request->size,
                'quantity'=>$request->quantity,
                'sku'=>$sku,
                'created_at'=>Carbon::now(),
            ]);
        }
        return back()->with('success', 'Inventory updated');
    }
    function inventory_del($id){
        Inventory::find($id)->delete();
        return back()->with('success', 'Product inventory removed');
    }
    function getGalImage(Request $request){
        $str = '';
        $image = Gallery::find($request->image_id);
        $delete_from = public_path('uploads/gallery/'.$image->image);
        unlink($delete_from);
        Gallery::find($request->image_id)->delete();
        $gallery_imgs = Gallery::where($request->product_id)->get();
        foreach($gallery_imgs as $imgs){
            $str .= "<img height='70' src=".public_path('uploads/gallery/').$imgs->image." alt='gallery image'><i data-id = ".$imgs->id." data-product = ".$imgs->product_id." class='fas fa-times-circle removeImage' style='color:red; position:absolute!important; top:5px;     right:5px; font-size:25px; opacity:0.7; z-index:10'></i>";
        }
        echo $str;
    }
    
}
