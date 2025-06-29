@extends('layouts.admin')
@section('content')
@can('tag_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h3>Tag List</h3></div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="item1Tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="item1-tab1" data-bs-toggle="tab" data-bs-target="#item1-content1" type="button" role="tab" aria-controls="item1-content1" aria-selected="true">
                                    Main
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
                                <form action="{{route('tag.check.delete')}}" method="POST">
                                    @csrf
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <th><input type="checkbox" id="chkAll" name="check_all"></th>
                                            <th>Sl</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($tags as $sl=>$tag)
                                        <tr>
                                            <td><input type="checkbox" class="chk" name="checked_id[]" value="{{$tag->id}}"></td>
                                            <td>{{$sl+1}}</td>
                                            <td>{{$tag->name}}</td>
                                            <td>
                                                @can('tag_edit')
                                                <i data-bs-toggle="modal" data-bs-target="#tag_edit" class="fa fa-edit text-success fa-xl cursor-pointer"></i>
                                                @endcan
                                                @can('tag_delete')
                                                <i class="fa fa-trash text-danger fa-xl del cursor-pointer" data-link={{route('tag.delete', $tag->id)}} ></i>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4"><h4 class="text-info">No Tags Found</h4></td>
                                        </tr>
                                        @endforelse
                                    </table>
                                    @can('tag_delete')
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-success">Delete Checked</button>
                                    </div>
                                    @endcan
                                </form>
                            </div>
                            <div class="tab-pane fade" id="item1-content2" role="tabpanel" aria-labelledby="item1-tab2">
                                <form action="{{route('tag.trash.action')}}" method="POST">
                                    @csrf
                                    <table class="table table-bordered text-center">
                                        <tr>
                                            <th><input type="checkbox" id="chkTrashAll" name="check_all"></th>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($tags_trashed as $sl=>$tag)
                                            <tr>
                                                <td><input type="checkbox" class="chkTrash" name="checked_id[]" value="{{$tag->id}}"></td>
                                                <td>{{$sl+1}}</td>
                                                <td>{{$tag->name}}</td>
                                                <td>
                                                    <a href="{{route('tag.restore', $tag->id)}}"><i class="fa fa-undo-alt text-success fa-xl"></i></a>
                                                    <a class="del_trash" data-link={{route('tag.trash.delete', $tag->id)}}><i class="fa fa-trash text-danger fa-xl  cursor-pointer"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4"><h4 class="text-info">Trash is empty</h4></td>
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
                @can('tag_add')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Tags</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('tag.store')}}" method="POST">
                            @csrf
                            <div class="mb-3 d-flex justify-content-between">
                                <label for="name" class="form-label">Name</label>
                                <span class="cursor-pointer" onclick="new_input()"><i class="fa fa-plus px-2"></i>Add new input</span>
                            </div>
                            <div class="extra">
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="name[]">
                                    @error('name[]')
                                        <strong class="text text-danger">{{$message}}</strong>
                                    @enderror
                                </div>
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
<div class="modal fade" id="tag_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name">Tag Name</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('jscript')
    <script>
        function new_input() {
        let main = document.querySelector('.extra');

        // Create a div wrapper
        let div = document.createElement('div');
        div.classList.add('mb-3', 'd-flex', 'align-items-center', 'gap-2');

        // Create input field
        let input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('name', 'name[]');
        input.classList.add('form-control');

        let removeBtn = document.createElement('i');
        removeBtn.classList.add('cursor-pointer', 'fas', 'fa-times-circle', 'fa-xl', 'text-danger');
        removeBtn.onclick = function () {
            main.removeChild(div); // Remove input field when clicked
        };

        // Append elements
        div.appendChild(input);
        div.appendChild(removeBtn);
        main.appendChild(div);
    }
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
        document.querySelectorAll('.del_trash').forEach(del=>{
            del.addEventListener('click', function(){
                    Swal.fire({
                    title: "Are you sure?",
                    text: "The item will be removed permanently!",
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
    <script>
        function toggleCheckbox(selector, targetClass) {
            document.querySelector(selector).addEventListener('click', function() {
            document.querySelectorAll(targetClass).forEach(chk => chk.checked = this.checked);
            });
        }
        toggleCheckbox("#chkAll", ".chk");
        toggleCheckbox("#chkTrashAll", ".chkTrash");
    </script>
@endsection