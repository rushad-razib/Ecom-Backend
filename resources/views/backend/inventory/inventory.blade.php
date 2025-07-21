@extends('layouts.admin')
@section('content')
@can('inventory_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Inventory List, <span class="text-info">{{$product_info->name}}</span></h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th class="text-wrap">SKU</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Quantity</th>
                                <th class="text-wrap">Price(per unit)</th>
                                <th class="text-wrap">Discounted Price(per unit)</th>
                                <th>Action</th>
                            </tr>
                            @forelse ($inventories as $sl=>$inventory)
                                <tr>
                                    <td>{{$sl+1}}</td>
                                    <td class="text-wrap">{{$inventory->sku}}</td>
                                    <td>{{$inventory->rel_to_color->name}}</td>
                                    <td>{{$inventory->rel_to_size->name}}</td>
                                    <td>{{$inventory->quantity}}</td>
                                    <td>{{$inventory->price}}</td>
                                    <td>{{($inventory->after_discount?$inventory->after_discount:'')}}</td>
                                    <td>
                                        @can('inventory_delete')
                                        <a data-link="{{route('inventory.del', $inventory->id)}}" class="text-danger del cursor-pointer"><i class="fas fa-trash fa-xl"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"><h5 class="text-danger text-center">Out of Stock</h5></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @can('inventory_add')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Inventory</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('inventory.store', $product_info->id)}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" readonly class="form-control" name="product_name" value="{{$product_info->name}}">
                            </div>
                            <div class="mb-3">
                                <select name="color" class="form-control" id="color_id">
                                    <option value="">Select Color</option>
                                    @foreach ($colors as $color)
                                        <option value="{{$color->id}}">{{$color->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select name="size" class="form-control" id="size_id">
                                    <option value="">Select Size</option>
                                    @foreach ($sizes as $size)
                                        <option value="{{$size->id}}">{{$size->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity">
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" name="price">
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
@endsection
@section('jscript')
    {{-- <script> 
        $(document).ready(function(){
            $('#color_id , #size_id').change(function(){
                let color_id = $('#color_id').val();
                let size_id = $('#size_id').val();
                let product_id = {{$product_info->id}};

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'/getprice',
                    type:'POST',
                    data:{'color_id':color_id, 'size_id':size_id, 'product_id':product_id},
                    success:function(data){
                        $('#price').val(data);
                    }
                });
            });
        });
    </script> --}}
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