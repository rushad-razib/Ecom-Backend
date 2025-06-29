@extends('layouts.admin')
@section('content')
@can('user_access')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>User List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($users as $sl=>$user)
                            <tr>
                                <td>{{$sl+1}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @can('user_delete')
                                    <a data-link="{{route('user.del', $user->id)}}" class="text-danger del cursor-pointer"><i class="fas fa-trash fa-xl"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        @can('user_registration')
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3>Add New User</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        @error('name')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        @error('email')
                            <strong class="text-danger">{{$message}}</strong>
                        @enderror
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password">
                            @error('password')
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                            @error('password_confirmation')
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                            <img width="200" id="blah">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    </div>
@else
    <h3>You dont have permission to view this page</h3>
@endcan
@endsection
@section('jscript')
<script>
    document.querySelectorAll('.del').forEach(del=>{
        del.addEventListener('click', function(){
                Swal.fire({
                title: "Are you sure?",
                text: "This user will be removed Permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    let link = this.getAttribute('data-link');
                    window.location.href = link;
                }
            });
        });
    });
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