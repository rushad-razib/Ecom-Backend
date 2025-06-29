@extends('layouts.admin')
@section('content')
@can('order_access')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 m-auto">
                <table class="table" id="order_table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Order ID</th>
                            <th>Total Price</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $sl=>$order)
                        <tr>
                            <td>{{$sl+1}}</td>
                            <td>{{$order->order_id}}</td>
                            <td>{{$order->total}}</td>
                            <td>{{$order->created_at->diffForHumans()}}</td>
                            <td>
                                @if ($order->status == 0)
                                    <span class="badge bg-primary">Pending</span>
                                @elseif($order->status == 1)
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status == 2)
                                    <span class="badge bg-warning">Shipping</span>
                                @elseif($order->status == 3)
                                    <span class="badge bg-success text-white">Delivered</span>
                                @elseif($order->status == 4)
                                    <span class="badge bg-dark">Pending Cancellation</span>
                                @else
                                    <span class="badge bg-success">Cancelled</span>
                                @endif
                            </td>
                            <td class="d-flex">
                                @can('order_status')
                                <form action="{{route('order.cancel', $order->id)}}" method="POST">
                                    @csrf
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" type="button" role="button" data-toggle="dropdown" aria-expanded="false">
                                          Select Action
                                        </a>
                                      
                                        <div class="dropdown-menu">
                                            <button name="action" value="0" class="dropdown-item py-2 {{$order->status == 0?'bg-secondary text-white':''}}">Pending</button>
                                            <button name="action" value="1" class="dropdown-item py-2 {{$order->status == 1?'bg-secondary text-white':''}}">Processing</button>
                                            <button name="action" value="2" class="dropdown-item py-2 {{$order->status == 2?'bg-secondary text-white':''}}">Shipping</button>
                                            <button name="action" value="3" class="dropdown-item py-2 {{$order->status == 3?'bg-secondary text-white':''}}">Delivered</button>
                                        </div>
                                      </div>
                                </form>
                                @endcan
                                @can('order_cancel')
                                @if($order->status != 5)
                                    <a class="btn btn-danger" href="{{route('cancel.manage', $order->id)}}">Cancel Order</button>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
        $('#order_table').DataTable();
    } );
</script>
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