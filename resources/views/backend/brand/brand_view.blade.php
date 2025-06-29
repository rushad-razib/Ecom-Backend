@extends('layouts.admin')
@section('content')
@can('brand_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Brand List</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="item1Tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="item1-tab1" data-bs-toggle="tab" data-bs-target="#item1-content1" type="button" role="tab" aria-controls="item1-content1" aria-selected="true">Main</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="item1-tab2" data-bs-toggle="tab" data-bs-target="#item1-content2" type="button" role="tab" aria-controls="item1-content2" aria-selected="false">Trash</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="item1TabContent">
                            <div class="tab-pane fade show active" id="item1-content1" role="tabpanel" aria-labelledby="item1-tab1">
                            <form action="{{route('brand.check')}}" method="POST">
                                @csrf
                                <table class="table table-bordered">
                                    <tr>
                                        <th><input type="checkbox" id="chkSelectAll" name="check_all"></th>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Logo</th>
                                        <th>Action</th>
                                    </tr>
                                    @forelse ($brands as $sl=>$brand)
                                    <tr>
                                        <td><input type="checkbox" class="chkDel" name="checked[]" value="{{$brand->id}}"></td>
                                        <td>{{$sl+1}}</td>
                                        <td>{{$brand->name}}</td>
                                        <td><img src="{{asset('uploads/brand')}}/{{$brand->image}}" alt=""></td>
                                        <td>
                                            @can('brand_edit')
                                            <a type="button" data-toggle="modal" data-id={{$brand->id}} data-name={{$brand->name}} data-img={{$brand->image}} data-slug={{$brand->slug}}  data-target="#exampleModal" class="info"><i class="fas fa-edit fa-xl"></i></a>
                                            @endcan
                                            @can('brand_delete')
                                            <a type="button" class="del text-danger" data-link="{{route('brand.delete', $brand->id)}}"><i class="fas fa-trash fa-xl"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center"><h4 class="text-info">Brand list is empty</h4></td>
                                        </tr>
                                    @endforelse
                                </table>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-danger">Delete Selected</button>
                                </div>
                            </form>
                            </div>
                            <div class="tab-pane fade" id="item1-content2" role="tabpanel" aria-labelledby="item1-tab2">
                                <form action="{{route('trash.brand.check')}}" method="POST">
                                    @csrf
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <th><input type="checkbox" id="chk_BrandTrashAll" name="check_all"></th>
                                            <th>Sl</th>
                                            <th>Name</th>
                                            <th>Logo</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($brands_trashed as $sl=>$trash)
                                            <tr>
                                                <td><input type="checkbox" class="chkBrandTrashAll" name="checked[]" value="{{$trash->id}}"></td>
                                                <td>{{$sl+1}}</td>
                                                <td>{{$trash->name}}</td>
                                                <td><img width="100" src="{{asset('uploads/brand')}}/{{$trash->image}}"></td>
                                                <td>
                                                    <a title="restore" href="{{route('brand.restore', $trash->id)}}" type="button" class="info text-success"><i class="fas fa-undo-alt fa-xl"></i></a>
                                                    <a title="delete" type="button" class="del text-danger" data-link="{{route('brand.trash.delete', $trash->id)}}"><i class="fas fa-trash fa-xl"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5"><h3 class="text-info">Trash is empty</h3></td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="mt-3">
                                        <button type="submit" name="action" class="btn btn-success" value="restore">Restore Checked</button>
                                        <button type="submit" name="action" class="btn btn-danger" value="delete">Delete Checked</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @can('brand_add')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Brand</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('brand.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control">
                                @error('name')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                <img width="200" id="blah"><br>
                                @error('image')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add</button>
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('brand.edit')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form_id" name="form_id">
                        <input type="hidden" class="form_slug">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form_name">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" class="form-control form_img" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                            <img width="100" class="show_img" alt="Brand image" id="blah">
                        </div>
                </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection
@section('jscript')
    <script>
        document.querySelectorAll('.info').forEach(info => {
            info.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const img = this.getAttribute('data-img');
                const slug = this.getAttribute('data-slug');
                
                document.querySelector('.form_id').value = id;
                document.querySelector('.form_name').value = name;
                document.querySelector('.form_slug').value = slug;

                const imagePath = `{{ asset('uploads/brand') }}/${img}`;
                
                const imgElement = document.querySelector('.show_img');
                imgElement.src = imagePath;
                document.querySelector('.modal-title').innerHTML = 'Edit Brand - '+name;
            });
        });
    </script>
    <script>
        document.querySelectorAll('.del').forEach(del=>{
            del.addEventListener('click', function(){
                    Swal.fire({
                    title: "Are you sure?",
                    text: "The item will be moved to trash!",
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
    <script>
        function toggleCheckbox(selector, targetClass) {
        document.querySelector(selector).addEventListener('click', function() {
        document.querySelectorAll(targetClass).forEach(chk => chk.checked = this.checked);
        });
        }
        toggleCheckbox("#chkSelectAll", ".chkDel");
        toggleCheckbox("#chk_BrandTrashAll", ".chkBrandTrashAll");
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
    @if (session('brand_deleted'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{session('brand_deleted')}}",
                showConfirmButton: false,
                timer: 1500
                });
        </script>
    @endif
@endsection