<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class taskController extends Controller
{
    function task_view(){
        $users = User::all();
        $tasks = Task::all();
        return view('backend.task.task_view', compact('users', 'tasks'));
    }
    function task_add(Request $request){
        Task::insert([
            'name'=>$request->name,
            'deadline'=>$request->deadline,
            'assigned_to'=>$request->assign_to,
            'assigned_by'=>Auth::id(),
            'created_at'=>Carbon::now(),
        ]);
        return back();
    }
    function task_del($task_id){
        Task::find($task_id)->delete();
        return back()->with('task_deleted', 'Task Deleted Successfully');
    }
    function my_task(){
        $tasks = Task::where('assigned_to', Auth::id())->get();
        return view('backend.task.my_task', [
            'tasks'=>$tasks,
        ]);
    }
    function task_comment(Request $request){
        Task::find($request->task_id)->update([
            'comment'=>$request->comment,
            'status'=>1,
        ]);
        return back();
    }
}
