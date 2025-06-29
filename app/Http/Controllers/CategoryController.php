<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    function category_view(){
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $categories_trashed = Category::onlyTrashed()->get();
        $subcategories_trashed = Subcategory::onlyTrashed()->get();
        return view('backend.category.category_view', [
            'categories'=>$categories,
            'subcategories'=>$subcategories,
            'categories_trashed'=>$categories_trashed,
            'subcategories_trashed'=>$subcategories_trashed,
        ]);
    }
    function category_store(Request $request){

        $request->validate([
            'name'=>['required', 'unique:categories'],
            'image'=>['required', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $slug = Str::lower(str_replace(' ', '-', $request->name)).'-'.random_int(50000, 60000);
        $image = $request->image;
        $extension = $image->extension();
        $filename = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image);
        $image->scale(300, 300);
        $image->save(public_path('uploads/category/').$filename);

        Category::insert([
            'name'=>$request->name,
            'slug'=>$slug,
            'image'=>$filename,
        ]);
        return back()->with('success', 'Category Added Successfully');
    }
    function category_edit(Request $request){
        $category = Category::find($request->form_id);
        $slug = Str::lower(str_replace(' ', '-', $request->name)).'-'.random_int(50000, 60000);
        if($request->image){
            $delete_from = public_path('uploads/category/').$category->image;
            unlink($delete_from);
            $image = $request->image;
            $extension = $image->extension();
            $filename = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->scale(300, 300);
            $image->save(public_path('uploads/category/').$filename);
            
            Category::find($request->form_id)->update([
                'name'=>$request->name,
                'image'=>$filename,
                'slug'=>$slug,
            ]);
        }
        else{
            Category::find($request->form_id)->update([
                'name'=>$request->name,
                'slug'=>$slug,
            ]);
        }
        
        return back()->with('success', 'Category Updated Successfully');
        
    }
    function category_delete($id){
        Category::find($id)->delete();
        return back()->with('category_deleted', 'Category Moved to Trash');
    }
    function category_trash(){
        $categories = Category::onlyTrashed()->get();
        return view('backend.category.trash', [
            'categories'=>$categories,
        ]);
    }
    function category_trash_delete($id){
        Category::onlyTrashed()->find($id)->forceDelete();
        return back()->with('category_deleted', 'Category Removed');
    }
    function category_restore($id){
        Category::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Category Restored');
    }
    function category_check(Request $request){
        foreach($request->checked as $checked){
            Category::find($checked)->delete();
        }
        return back()->with('category_deleted', 'Categories Moved to Trash');
    }
    function trash_category_check(Request $request){
        $action = $request->action;
        if($action == 'delete'){
            foreach($request->checked as $checked){
                Category::onlyTrashed()->find($checked)->forceDelete();
            }
            return back()->with('category_deleted', 'Categories Removed');
        }
        elseif($action == 'restore'){
            foreach($request->checked as $checked){
                Category::onlyTrashed()->find($checked)->restore();
            }
            return back()->with('success', 'Categories Restored');
        }
    }

    function test_view(){
        $categories = Category::all();
        return view('backend.category.test', [
            'categories'=>$categories,
        ]);
    }
}
