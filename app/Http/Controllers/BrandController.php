<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    function brand_view(){
        $brands = Brand::all();
        $brands_trashed = Brand::onlyTrashed()->get();
        return view('backend.brand.brand_view', [
            'brands' => $brands,
            'brands_trashed' => $brands_trashed,
        ]);
    }
    function brand_store(Request $request){
        $request->validate([
            'name'=>'required|unique:brands',
            'image'=>['required', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);
        $slug = Str::lower(str_replace(' ', '-', $request->name)).'-'.random_int(50000, 60000);
        $image = $request->image;
        $extension = $image->extension();
        $filename = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);
        $image->scale(300, 300);
        $image->save(public_path('uploads/brand/').$filename);

        Brand::insert([
            'name'=>ucwords(strtolower($request->name)),
            'slug'=>$slug,
            'image'=>$filename,
        ]);
        return back()->with('success', 'Brand Added Successfully');
    }
    function brand_delete($id){
        Brand::find($id)->delete();
        return back()->with('brand_deleted', 'Brand Moved to Trash');
    }
    function brand_edit(Request $request){
        $brand = Brand::find($request->form_id);
        $slug = Str::lower(str_replace(' ', '-', $request->name)).'-'.random_int(50000, 60000);
        if($request->image){
            $delete_from = public_path('uploads/brand/').$brand->image;
            unlink($delete_from);
            $image = $request->image;
            $extension = $image->extension();
            $filename = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->scale(300, 300);
            $image->save(public_path('uploads/brand/').$filename);
            
            Brand::find($request->form_id)->update([
                'name'=>$request->name,
                'image'=>$filename,
                'slug'=>$slug,
            ]);
        }
        else{
            Brand::find($request->form_id)->update([
                'name'=>$request->name,
                'slug'=>$slug,
            ]);
        }
        
        return back()->with('success', 'Brand Updated Successfully');
    }
    function brand_check(Request $request){
        foreach($request->checked as $checked){
            Brand::find($checked)->delete();
        }
        return back()->with('brand_deleted', 'Brand Moved to Trash');
    }
    function brand_restore($id){
        Brand::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Brand Restored');
    }
    function brand_trash_delete($id){
        Brand::onlyTrashed()->find($id)->forceDelete();
        return back()->with('success', 'Brand Removed');
    }
    function trash_brand_check(Request $request){
        $action = $request->action;
        if($action == 'delete'){
            foreach($request->checked as $checked){
                Brand::onlyTrashed()->find($checked)->forceDelete();
            }
            return back()->with('brand_deleted', 'Categories Removed');
        }
        elseif($action == 'restore'){
            foreach($request->checked as $checked){
                Brand::onlyTrashed()->find($checked)->restore();
            }
            return back()->with('success', 'Categories Restored');
        }
    }
}
