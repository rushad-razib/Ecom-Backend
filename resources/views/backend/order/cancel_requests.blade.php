@extends('layouts.admin')
@section('content')
@can('cancel_requests')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Cancelled Orders List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            @forelse ($cancellation_reqs as $sl=>$req)
                                <tr>
                                    <td>{{$sl+1}}</td>
                                    <td>{{$req->order_id}}</td>
                                    <td>{{$req->created_at->diffForHumans()}}</td>
                                    <td>&#2547; {{\App\Models\Order::where('order_id', $req->order_id)->first()->total}}</td>
                                    <td class="d-flex gap-1">
                                        <a href="{{route('cancel.reason', $req->id)}}" class="btn btn-primary">View Reason</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="fw-bold text-center text-info fs-4">No Cancellation request found</td>
                                </tr>
                            @endforelse
                        </table>
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
    @if(session('success'))
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
            });
            Toast.fire({
            icon: "success",
            title: "{{session('success')}}"
            });
    </script>
    @endif
@endsection