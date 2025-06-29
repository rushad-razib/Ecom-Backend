@extends('layouts.admin')
@section('content')
@can('variation_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Color List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Color Name</th>
                                <th>Color Code</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($colors as $sl=>$color)
                                <tr>
                                    <td>{{$sl+1}}</td>
                                    <td>{{$color->name}}</td>
                                    <td><span class="px-4 py-2" style="background:{{$color->code}};"></span></td>
                                    <td>
                                        @can('variation_delete')
                                        <a type="button" data-link="{{route('color.del', $color->id)}}" class="text-danger del"><i class="fas fa-trash fa-xl"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Size List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Size Name</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($sizes as $sl=>$size)
                                <tr>
                                    <td>{{$sl+1}}</td>
                                    <td>{{$size->name}}</td>
                                    <td>
                                        <a type="button" data-link="{{route('size.del', $size->id)}}" class="text-danger del"><i class="fas fa-trash fa-xl"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @can('color_add')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Color</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('color.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Color Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Color Code</label>
                                <input type="text" class="form-control" name="code">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add Color</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan
                @can('size_add')
                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Add Size</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('size.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Size Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add Size</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
@else
<h3>You do not have permission to view this page</h3>
@endcan
@endsection
@section('jscript')
    <script>
        document.querySelectorAll('.del').forEach(del=>{
            del.addEventListener('click', function(){
                    Swal.fire({
                    title: "Are you sure?",
                    text: "The item will be removed Permanently!",
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