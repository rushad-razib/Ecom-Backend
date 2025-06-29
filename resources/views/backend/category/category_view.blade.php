@extends('layouts.admin')
@section('content')
@can('category_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Categories</h3>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="item1Tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="item1-tab1" data-bs-toggle="tab" data-bs-target="#item1-content1" type="button" role="tab" aria-controls="item1-content1" aria-selected="true">
                                    List
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="item1-tab2" data-bs-toggle="tab" data-bs-target="#item1-content2" type="button" role="tab" aria-controls="item1-content2" aria-selected="false">
                                    Trash
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="item1TabContent">
                            <div class="tab-pane fade show active" id="item1-content1" role="tabpanel" aria-labelledby="item1-tab1">
                                <form action="{{route('category.check')}}" method="POST">
                                    @csrf
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <th><input type="checkbox" id="chkSelectAll" name="check_all"></th>
                                            <th>Sl</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($categories as $sl=>$category)
                                            <tr>
                                                <td><input type="checkbox" class="chkDel" name="checked[]" value="{{$category->id}}"></td>
                                                <td>{{$sl+1}}</td>
                                                <td>{{$category->name}}</td>
                                                <td><img width="100" src="{{asset('uploads/category')}}/{{$category->image}}"></td>
                                                <td>
                                                    @can('category_edit')
                                                    <a data-bs-toggle="modal" data-bs-target="#cat_edit" data-id={{$category->id}} data-name={{$category->name}} data-img={{$category->image}} data-slug={{$category->slug}} class="catInfo"><i class="fas fa-edit fa-xl"></i></a>
                                                    @endcan
                                                    @can('category_delete')
                                                    <a type="button" class="del text-danger" data-link="{{route('category.delete', $category->id)}}"><i class="fas fa-trash fa-xl"></i></a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=5 class="text-info text-xl"><h3>No Categories found</h3></td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    @can('category_delete')
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-success">Delete Checked</button>
                                    </div>
                                    @endcan
                                </form>
                            </div>
                            <div class="tab-pane fade" id="item1-content2" role="tabpanel" aria-labelledby="item1-tab2">
                                <form action="{{route('trash.category.check')}}" method="POST">
                                    @csrf
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <th><input type="checkbox" id="chkTrashAll" name="check_all"></th>
                                            <th>Sl</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($categories_trashed as $sl=>$trash)
                                            <tr>
                                                <td><input type="checkbox" class="chkTrashDel" name="checked[]" value="{{$trash->id}}"></td>
                                                <td>{{$sl+1}}</td>
                                                <td>{{$trash->name}}</td>
                                                <td><img width="100" src="{{asset('uploads/category')}}/{{$trash->image}}"></td>
                                                <td>
                                                    <a title="restore" href="{{route('category.restore', $trash->id)}}" type="button" class="info text-success"><i class="fas fa-undo-alt fa-xl"></i></a>
                                                    <a title="delete" type="button" class="del text-danger" data-link="{{route('category.trash.delete', $trash->id)}}"><i class="fas fa-trash fa-xl"></i></a>
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
                <div class="card mt-3">
                    <div class="card-header"><h4>Sub Categories</h4></div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="item2Tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="item2-tab1" data-bs-toggle="tab" data-bs-target="#item2-content1" type="button" role="tab" aria-controls="item2-content1" aria-selected="true">
                                    List
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="item2-tab2" data-bs-toggle="tab" data-bs-target="#item2-content2" type="button" role="tab" aria-controls="item2-content2" aria-selected="false">
                                    Trash
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="item2TabContent">
                            <div class="tab-pane fade show active" id="item2-content1" role="tabpanel" aria-labelledby="item2-tab1">
                                <div class="row">
                                    @foreach($categories as $category)
                                    <div class="col-lg-6 my-2">
                                        <div class="card">
                                            <div class="card-header"><h5>{{$category->name}}</h5></div>
                                            <div class="card-body">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Icon</th>
                                                        <th>Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @forelse ($category->rel_to_subcategory as $subcategory)
                                                    <tr>
                                                        <td><img src="{{asset('uploads/subcategory')}}/{{$subcategory->icon}}" alt=""></td>
                                                        <td class="text-wrap">{{$subcategory->name}}</td>
                                                        <td>
                                                            @can('subcategory_edit')
                                                            <a data-bs-toggle="modal" data-bs-target="#sub_cat_edit" data-id={{$subcategory->id}} data-name={{$subcategory->name}} data-img={{$subcategory->icon}} data-slug={{$subcategory->slug}}  class="subCatInfo"><i class="fas fa-edit fa-xl"></i></a>
                                                            @endcan
                                                            @can('subcategory_delete')
                                                            <a type="button" class="del_sub" data-link="{{route('subcategory.delete', $subcategory->id)}}"><i class="fas fa-trash fa-lg"></i></a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Subcategories found</td>
                                                    </tr>
                                                    @endforelse
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="item2-content2" role="tabpanel" aria-labelledby="item2-tab2">
                                <form action="{{route('trash.subcategory.check')}}" method="POST">
                                    @csrf
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <th><input type="checkbox" id="chkSubTrashAll" name="check_all"></th>
                                            <th>Sl</th>
                                            <th>Name</th>
                                            <th>Icon</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($subcategories_trashed as $sl=>$trash)
                                            <tr>
                                                <td><input type="checkbox" class="chkSubTrashDel" name="checked[]" value="{{$trash->id}}"></td>
                                                <td>{{$sl+1}}</td>
                                                <td>{{$trash->name}}</td>
                                                <td><img width="100" src="{{asset('uploads/subcategory')}}/{{$trash->icon}}"></td>
                                                <td>
                                                    <a title="restore" href="{{route('subcategory.restore', $trash->id)}}" type="button" class="info text-success"><i class="fas fa-undo-alt fa-xl"></i></a>
                                                    <a title="delete" type="button" class="del text-danger" data-link="{{route('subcategory.trash.delete', $trash->id)}}"><i class="fas fa-trash fa-xl"></i></a>
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
                @can('category_add')
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Add Category</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('category.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name">
                                @error('name')
                                    <strong class="text text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" name="image">
                                @error('image')
                                <strong class="text text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan
                @can('subcategory_add')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Subcategory</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('subcategory.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name">
                                @error('name')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label"></label>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon</label>
                                <input type="file" class="form-control" name="icon">
                                @error('icon')
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
    <!-- Category Modal -->
    <div class="modal fade" id="cat_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('category.edit')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form_id" name="form_id">
                        <input type="hidden" class="form_slug">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control form_name">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">image</label>
                            <input type="file" name="image" class="form-control form_img" onchange="document.getElementById('cat_preview').src = window.URL.createObjectURL(this.files[0])">
                            <img width="150" class="mt-2 show_img" alt="Category image" id="cat_preview">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                    </form>
            </div>
        </div>
    </div>
    <!-- Subcategory Modal -->
    <div class="modal fade" id="sub_cat_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('subcategory.edit')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="sub_form_id" name="form_id">
                        <input type="hidden" class="sub_form_slug">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control sub_form_name">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">image</label>
                            <input type="file" name="image" class="form-control sub_form_img" onchange="document.getElementById('cat_preview').src = window.URL.createObjectURL(this.files[0])">
                            <img width="150" class="mt-2 sub_show_img" alt="sub category image" id="sub_cat_preview">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

@endsection
@section('jscript')

    <script>
        document.querySelectorAll('.catInfo').forEach(info => {
            info.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const img = this.getAttribute('data-img');
                const slug = this.getAttribute('data-slug');

                document.querySelector('.form_id').value = id;
                document.querySelector('.form_name').value = name;
                document.querySelector('.form_slug').value = slug;

                const imagePath = `{{ asset('uploads/category') }}/${img}`;
                
                const imgElement = document.querySelector('.show_img');
                imgElement.src = imagePath;
                document.querySelector('.modal-title').innerHTML = 'Edit Category - '+name;
                
            });
        });
    </script>
    <script>
        document.querySelectorAll('.subCatInfo').forEach(info => {
            info.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const img = this.getAttribute('data-img');
                const slug = this.getAttribute('data-slug');
                
                document.querySelector('.sub_form_id').value = id;
                document.querySelector('.sub_form_name').value = name;
                document.querySelector('.sub_form_slug').value = slug;

                const imagePath = "{{ asset('uploads/subcategory') }}/"+img;
                
                const imgElement = document.querySelector('.sub_show_img');
                imgElement.src = imagePath;
                document.querySelector('.modal-title').innerHTML = 'Edit Subcategory - '+name;
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
        document.querySelectorAll('.del_sub').forEach(del=>{
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
    @if (session('category_deleted'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{session('category_deleted')}}",
                showConfirmButton: false,
                timer: 1500
                });
        </script>
    @endif 
    @if (session('sub_deleted'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{session('sub_deleted')}}",
                showConfirmButton: false,
                timer: 1500
                });
        </script>
    @endif 
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
    @if (session('subcategory_success'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{session('subcategory_success')}}",
                showConfirmButton: false,
                timer: 1500
                });
        </script>
    @endif 
    <script>
        function toggleCheckbox(selector, targetClass) {
            document.querySelector(selector).addEventListener('click', function() {
            document.querySelectorAll(targetClass).forEach(chk => chk.checked = this.checked);
            });
        }
        toggleCheckbox("#chkSelectAll", ".chkDel");
        toggleCheckbox("#chkTrashAll", ".chkTrashDel");
        toggleCheckbox("#chkSubTrashAll", ".chkSubTrashDel");
    </script>
@endsection