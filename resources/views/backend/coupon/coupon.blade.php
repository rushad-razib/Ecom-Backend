@extends('layouts.admin')
@section('content')
@can('coupon_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h3>Coupon List</h3></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Coupon</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Validity</th>
                                <th>Limit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @forelse ($coupons as $sl=>$coupon)
                                <tr>
                                    <td>{{$sl+1}}</td>
                                    <td>{{$coupon->coupon}}</td>
                                    <td>{{$coupon->type == 1?'Percentage':'Solid'}}</td>
                                    <td>{{$coupon->amount}}</td>
                                    <td class="text-wrap">{{$coupon->validity}}</td>
                                    <td>{{$coupon->limit}}</td>
                                    <td>
                                        @can('coupon_status')
                                        <a href="{{route('coupon.status', $coupon->id)}}" class="badge text-bg-{{$coupon->status==0?'secondary':'primary'}}">{{$coupon->status==0?'Inactive':'Active'}}</a>
                                        @endcan
                                    </td>
                                    <td>
                                        @can('coupon_delete')
                                        <a data-link="{{route('coupon.del', $coupon->id)}}" class="text-danger del cursor-pointer"><i class="fas fa-trash fa-xl"></i></a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center"><h4 class="text-info">List is empty</h4></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @can('coupon_add')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Coupon</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('coupon.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="coupon" class="form-label">Name</label>
                                <input type="text" class="form-control" name="coupon">
                                @error('coupon')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="1">Percentage</option>
                                    <option value="2">Solid</option>
                                </select>
                                @error('type')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount">
                                @error('amount')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="validity" class="form-label">Validity</label>
                                <input type="date" class="form-control" name="validity">
                                @error('validity')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="limit" class="form-label">Limit</label>
                                <input type="number" class="form-control" name="limit">
                                @error('limit')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Add</button>
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