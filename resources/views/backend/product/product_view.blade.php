@extends('layouts.admin')
@section('content')
@can('product_access')
    <div class="container">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Product Management</h3>
                    <a type="button" href="{{route('product.store.view')}}" class="btn btn-primary text-center d-flex align-items-center justify-content-center"><i class="fas fa-plus fa-xl mr-2"></i><p class="fw-bold">Add New</p></a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="item1Tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="item1-tab1" data-bs-toggle="tab" data-bs-target="#item1-content1" type="button" role="tab" aria-controls="item1-content1" aria-selected="true">List</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="item1-tab2" data-bs-toggle="tab" data-bs-target="#item1-content2" type="button" role="tab" aria-controls="item1-content2" aria-selected="false">Trash</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="item1TabContent">
                        <div class="tab-pane fade show active pt-3" id="item1-content1" role="tabpanel" aria-labelledby="item1-tab1">
                            <table class="table" id="product_table">
                                <thead>
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Tags</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td><img src="{{asset('uploads/product')}}/{{$product->image}}" width="70" alt=""></td>
                                            <td class="text-wrap">{{$product->name}}</td>
                                            <td>{{$product->rel_to_category->name}}</td>
                                            
                                            <td>{{$product->rel_to_subcategory->name}}</td>
                                            @php
                                                $tag_list = [];
                                                if($product->tag_id){
                                                    $tags = explode(',', $product->tag_id);
                                                    foreach ($tags as $tag_id) {
                                                        $tag = \App\Models\Tag::find($tag_id);
                                                        array_push($tag_list, $tag->name);
                                                    }
                                                }
                                            @endphp
                                            <td class="text-wrap">{{implode(',', $tag_list)}}</td>
                                            <td>{{$product->price}}</td>
                                            <td>{{($product->discount?$product->discount.'%':'')}}</td>
                                            <td class="{{(\App\Models\Inventory::where('product_id', $product->id)->count() != 0?'text-info':'text-danger')}}">{{(\App\Models\Inventory::where('product_id', $product->id)->count() != 0?'In Stock':'Out of Stock')}}</td>
                                            <td>
                                                @can('inventory_access')
                                                <a href="{{route('product.inventory', $product->id)}}" title="inventory"><i class="fas fa-database fa-xl"></i></a>
                                                @endcan
                                                @can('product_add')
                                                <a type="button" title="edit" href="{{route('product.edit', $product->id)}}"><i class="fas fa-edit fa-xl"></i></a>
                                                @endcan
                                                @can('product_delete')
                                                <a type="button" title="delete" class="del_product text-danger" data-link="{{route('product.delete', $product->id)}}"><i class="fas fa-trash fa-xl"></i></a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade pt-3" id="item1-content2" role="tabpanel" aria-labelledby="item1-tab2">
                            <table class="table" id="trash_table">
                                <thead>
                                    <tr>
                                        <th>Thumbnail</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Tags</th>
                                        <th>Discount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products_trashed as $trash)
                                    <tr>
                                        <td><img src="{{asset('uploads/product')}}/{{$trash->image}}" width="70" alt=""></td>
                                        <td class="text-wrap">{{$trash->name}}</td>
                                        <td>{{$trash->rel_to_category->name}}</td>
                                        <td>{{$trash->rel_to_subcategory->name}}</td>
                                        @php
                                                $tag_list = [];
                                                if($trash->tag_id){
                                                    $tags = explode(',', $trash->tag_id);
                                                    foreach ($tags as $tag_id) {
                                                        $tag = \App\Models\Tag::find($tag_id);
                                                        array_push($tag_list, $tag->name);
                                                    }
                                                }
                                        @endphp
                                        <td>{{implode(',', $tag_list)}}</td>
                                        <td>{{($trash->discount?$trash->discount.'%':'')}}</td>
                                        <td>
                                            <a title="restore" href="{{route('product.restore', $trash->id)}}" type="button" class="info text-success"><i class="fas fa-undo-alt fa-xl"></i></a>
                                            <a type="button" class="del_product_trash text-danger" data-link="{{route('product.trash.delete', $trash->id)}}"><i class="fas fa-trash fa-xl"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@else
<h3>You do not have permission to view this page</h3>
@endcan

    
@endsection
@section('jscript')
    
    <script>
        $(document).ready( function () {
            $('#product_table').DataTable();
        } );
    </script>
    <script>
        $(document).ready( function () {
            $('#trash_table').DataTable();
        } );
    </script>
    
    
    <script>
        $('#select-tags').selectize({ sortField: 'text' });
    </script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
    <script>
        document.querySelectorAll('.del_product').forEach(del=>{
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
        document.querySelectorAll('.del_product_trash').forEach(del=>{
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