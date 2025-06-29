@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>Profile Edit</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.update')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input type="text" class="form-control" name="name" value="{{Auth::user()->name}}">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email" value="{{Auth::user()->email}}">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3>Password Update</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.pass')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="cur_pass" class="form-label">Current Password</label>
                                <input type="password" class="form-control" name="cur_pass">
                                @error('cur_pass')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                                @if (session('cur_pass_err'))
                                    <strong class="text-danger">{{session('cur_pass_err')}}</strong>                            
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
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
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white fs-6">
                        <h3 class="fs-6">Upload Profile Picture</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('user.photo')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" name="photo">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jscript')
    @if (session('user_updated'))
        <script>
            Swal.fire({
            position: "top-end",
            icon: "success",
            title: "{{session('user_updated')}}",
            showConfirmButton: false,
            timer: 1500
        });
        </script>
    @endif
@endsection