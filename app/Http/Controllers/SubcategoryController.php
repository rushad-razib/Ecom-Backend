<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    function subcategory_store(Request $request){
        $request->validate([
            'name'=>'required|unique:subcategories',
            'category'=>'required',
            'icon'=>'required',
        ]);
        $slug = Str::lower(str_replace(' ', '-', $request->name)).'-'.random_int(50000, 60000);
        $icon = $request->icon;
        $extension = $icon->extension();
        $filename = uniqid().'.'.$extension;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($icon);
        $image->scale(300, 300);
        $image->save(public_path('uploads/subcategory/').$filename);
        Subcategory::insert([
            'name'=>$request->name,
            'category_id'=>$request->category,
            'icon'=>$filename,
            'slug'=>$slug,
        ]);
        return back()->with('subcategory_success', 'Subcategory Added Successfully');
    }
    function subcategory_delete($id){
        Subcategory::find($id)->delete();
        return back()->with('sub_deleted', 'Subcategory moved to trash');
    }
    function subcategory_restore($id){
        Subcategory::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Subcategory Restored');
    }
    function subcategory_trash_delete($id){
        Subcategory::onlyTrashed()->find($id)->forcedelete();
        return back()->with('sub_deleted', 'Subcategory Removed');
    }
    function trash_subcategory_check(Request $request){
        $action = $request->action;
        if($action == 'delete'){
            foreach($request->checked as $checked){
                Subcategory::onlyTrashed()->find($checked)->forceDelete();
            }
            return back()->with('sub_deleted', 'Sub Categories Removed');
        }
        elseif($action == 'restore'){
            foreach($request->checked as $checked){
                Subcategory::onlyTrashed()->find($checked)->restore();
            }
            return back()->with('success', 'Sub Categories Restored');
        }
    }
    function subcategory_edit(Request $request){
        $subcategory = Subcategory::find($request->form_id);
        $slug = Str::lower(str_replace(' ', '-', $request->name)).'-'.random_int(50000, 60000);
        if($request->image){
            $delete_from = public_path('uploads/subcategory/').$subcategory->icon;
            unlink($delete_from);
            $image = $request->image;
            $extension = $image->extension();
            $filename = uniqid().'.'.$extension;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($image);
            $image->scale(300, 300);
            $image->save(public_path('uploads/subcategory/').$filename);
            
            Subcategory::find($request->form_id)->update([
                'name'=>$request->name,
                'icon'=>$filename,
                'slug'=>$slug,
            ]);
        }
        else{
            Subcategory::find($request->form_id)->update([
                'name'=>$request->name,
                'slug'=>$slug,
            ]);
        }
        
        return back()->with('success', 'Subcategory Updated Successfully');
    }
}
