<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    function role_view(){
        $permissions = Permission::all();
        $users = User::all();
        $roles = Role::all();
        return view('backend.role.role_view', [
            'permissions'=>$permissions,
            'users'=>$users,
            'roles'=>$roles,
        ]);
    }
    function permission_store(Request $request){
        Permission::create(['name' => $request->name]);
        return back()->with('success', 'New permission Added');
    }
    function permission_delete($id){
        Permission::find($id)->delete();
        return back()->with('success', 'Permission removed');
    }
    function role_store(Request $request){
        $request->validate([
            'name'=>'required',
        ]);
        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);
        return back()->with('success', 'New role created');
    }
    function role_assign(Request $request){
        $user = User::find($request->user);
        $user->assignRole($request->role);
        return back()->with('success', 'Role assigned to user');
    }
    function role_edit($id){
        $role = Role::find($id);
        $permissions = Permission::all();
        return view('backend.role.role_edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }
    function role_sync(Request $request, $id){
        $role = Role::find($id);
        $role->syncPermissions($request->permissions);
        Role::find($id)->update([
            'name'=>$request->name,
        ]);
        return redirect()->route('role.view')->with('success', 'Role edited');
    }
    function role_delete($id){
        $role = Role::find($id);
        DB::table('role_has_permissions')->where('role_id', $id)->delete();
        Role::find($id)->delete();
        return back()->with('success', 'Role removed');
    }
    function role_unassign($id){
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        return back()->with('success', 'User role removed');
    }
}
