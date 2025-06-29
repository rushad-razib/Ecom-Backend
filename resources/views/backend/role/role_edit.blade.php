@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Role Edit, <strong class="text-info">{{$role->name}}</strong></h3>
                </div>
                <div class="card-body">
                    <form action="{{route('role.sync', $role->id)}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{$role->name}}">
                            @error('name')
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                        <div class="d-flex align-items-center flex-wrap">
                            @foreach ($permissions as $permission)
                            <div class="d-flex flex-wrap p-1">
                                <input {{($role->hasPermissionTo($permission)?'checked':'')}} type="checkbox" name="permissions[]" id="per{{$permission->name}}" value="{{$permission->name}}">
                                <label for="per{{$permission->name}}" class="pl-1 m-0">{{$permission->name}}</label>
                            </div>
                            @endforeach
                        </div>
                        <div class="my-3">
                            <button class="btn btn-primary" type="submit">Update Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection