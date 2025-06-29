@extends('layouts.admin')
@section('content')
@can('role_access')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h3>User Roles</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($users as $sl=>$user)
                            <tr>
                                <td>{{$sl+1}}</td>
                                <td>{{$user->name}}</td>
                                <td>
                                    @forelse ($user->getRoleNames() as $role)
                                        <span class="badge badge-primary py-1">{{$role}}</span>
                                    @empty
                                        <span class="badge badge-info">Unassigned</span>
                                    @endforelse
                                </td>
                                <td>
                                    @can('role_unassign')
                                    <a href="{{route('role.unassign', $user->id)}}" class="btn btn-danger">Unassign</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Role List</h3>
                </div>
                <div class="card-body">
                    <table>
                        <tr>
                            <th>Role</th>
                            <th>Permission</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($roles as $role)  
                        <tr>
                            <td>{{$role->name}}</td>
                            <td class="text-wrap py-2">
                                @foreach ($role->getPermissionNames() as $permission)
                                    <span class="badge badge-primary py-1">{{$permission}}</span>
                                @endforeach
                            </td>
                            <td class="text-nowrap">
                                @can('role_edit')
                                <a href="{{route('role.edit', $role->id)}}" title="Edit" class="text-primary cursor-pointer"><i class="fa-solid fa-pen-to-square fa-xl"></i></i></a>
                                @endcan
                                @can('role_delete')
                                <a data-link="{{route('role.delete', $role->id)}}" title="Delete" class="text-danger del cursor-pointer"><i class="fas fa-trash fa-xl"></i></a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @can('role_assign')
            <div class="card">
                <div class="card-header">
                    <h3>Assign Role to User</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('role.assign')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select name="user" id="user">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="role" id="role">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                <option value="{{$role->name}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
            @endcan
            @can('role_add')
            <div class="card">
                <div class="card-header">
                    <h3>Add New Role</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('role.store')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control">
                            @error('name')
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center flex-wrap">
                            @foreach ($permissions as $permission)
                            <div class="d-flex flex-wrap p-1">
                                <input type="checkbox" name="permissions[]" id="per{{$permission->name}}" value="{{$permission->name}}">
                                <label for="per{{$permission->name}}" class="pl-1 m-0">{{$permission->name}}</label>
                            </div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Add Role</button>
                        </div>
                    </form>
                </div>
            </div>
            @endcan
            @can('permission_add')
            <div class="card">
                <div class="card-header">
                    <h3>Add New Permission</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('permission.store')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Add</button>
                        </div>
                    </form>
                    @can('permission_delete')
                    @foreach ($permissions as $permission)
                    <a class="del cursor-pointer" data-link="{{route('permission.delete', $permission->id)}}"><span class="badge badge-primary my-1">{{$permission->name}} <i class="fas fa-times"></i></span></a>
                    @endforeach
                    @endcan
                </div>
            </div>
            @endcan
        </div>
    </div>
@else
    <h3>You dont have permission to view this page</h3>
@endcan
@endsection
@section('jscript')
<script>
    $('.del').click(function(){
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
        if (result.isConfirmed) {
            let link = this.getAttribute('data-link')
            window.location.href = link
        }
        });
    })
</script>
@if (session('success'))
    <script>
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "{{session('success')}}",
            showConfirmButton: false,
            timer: 1500
            });
    </script>
@endif

@endsection