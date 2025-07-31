@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Coupon List</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Validity</th>
                                <th>Limit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($coupons as $sl=>$coupon)
                            <tr>
                                <td>{{$sl+1}}</td>
                                <td>{{$coupon->coupon}}</td>
                                <td>{{$coupon->type==1?'Percentage':'Solid'}}</td>
                                <td>{{$coupon->amount}}</td>
                                <td>{{$coupon->validity->diffForHumans()}}</td>
                                <td>{{$coupon->limit}}</td>
                                <td>
                                @if ($coupon->status==0)
                                <span class="badge badge-primary">Active</span>
                                @else
                                <span class="badge badge-danger">Inactive</span>
                                @endif
                                </td>
                                <td><a href="{{route('coupon.delete', $coupon->id)}}" class="btn btn-danger">Delete</a></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Coupon</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('coupon.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="coupon" class="form-label">Coupon Name</label>
                                <input type="text" name="coupon" class="form-control">
                                @error('coupon')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">Select Coupon Type</option>
                                    <option value="1">Percentage</option>
                                    <option value="2">Solid</option>
                                </select>
                                @error('type')
                                    <strong class="text-danger">{{$message}}</strong>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" name="amount" class="form-control">
                            </div>
                            @error('amount')
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                            <div class="mb-3">
                                <label for="validity" class="form-label">Validity</label>
                                <input type="date" name="validity" class="form-control">
                            </div>
                            @error('validity')
                                <strong class="text-danger">{{$message}}</strong>
                            @enderror
                            <div class="mb-3">
                                <label for="limit" class="form-label">Purchase Limit (Optional)</label>
                                <input type="number" name="limit" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" type="submit">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jscript')
    @if (session('success'))
        <script>
            Swal.fire({
                position: "bottom-end",
                icon: "success",
                title: "{{session('success')}}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
@endsection