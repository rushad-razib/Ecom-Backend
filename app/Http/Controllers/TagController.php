<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagController extends Controller
{
    function tag_view(){
        $tags = Tag::all();
        $tags_trashed = Tag::onlyTrashed()->get();
        return view('backend.tag.tag_view', [
            'tags'=>$tags,
            'tags_trashed'=>$tags_trashed,
        ]);
    }
    function tag_store(Request $request){
        foreach($request->name as $tag){
            Tag::insert([
                'name'=>$tag,
                'created_at'=>Carbon::now(),
            ]);
        }
        return back()->with('success', 'Tags added successfully');
    }
    function tag_delete($id){
        Tag::find($id)->delete();
        return back()->with('success', 'Tag moved to Trash');
    }
    function tag_trash_delete($id){
        Tag::onlyTrashed()->find($id)->forceDelete();
        return back()->with('success', 'Tag removed');
    }
    function tag_check_delete(Request $request){
        foreach ($request->checked_id as $checked) {
            Tag::find($checked)->delete();
        }
        return back()->with('success', 'Tags moved to Trash');
    }
    function tag_restore($id){
        Tag::onlyTrashed()->find($id)->restore();
        return back()->with('success', 'Tag restored');
    }
    function tag_trash_action(Request $request){
        if ($request->action == 'restore') {
            foreach ($request->checked_id as $tags) {
                Tag::onlyTrashed()->find($tags)->restore();
            }
            return back()->with('success', 'Tags restored');
        }
        elseif ($request->action == 'delete') {
            foreach ($request->checked_id as $tags) {
                Tag::onlyTrashed()->find($tags)->forceDelete();
            }
            return back()->with('success', 'Tags removed');
        }
    }
}
