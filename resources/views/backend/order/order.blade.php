@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Order List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table" id="order_table">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Order ID</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Total (&#2547;)</th>
                                    <th class="text-center">Location</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $sl=>$order)
                            <tr>
                                <td class="text-center">{{ $sl+1 }}</td>
                                <td class="text-center">{{ $order->order_id }}</td>
                                <td class="text-center">{{ $order->rel_to_customer->name }}</td>
                                <td class="text-center">{{ $order->total }}</td>
                                <td class="text-center">{{\App\Models\Billing::where('order_id', $order->order_id)->first()->street_address}}</td>
                                <td class="text-center">
                                    <form action="{{route('order.status', $order->id)}}" method="POST">
                                    @csrf
                                    <select name="status" id="" onchange="this.form.submit();">
                                        <option value="0" {{ $order->status==0?'selected':'' }}>Pending</option>
                                        <option value="1" {{ $order->status==1?'selected':'' }}>Processing</option>
                                        <option value="2" {{ $order->status==2?'selected':'' }}>Dispatched</option>
                                        <option value="3" {{ $order->status==3?'selected':'' }}>Received</option>
                                        <option value="4" {{ $order->status==4?'selected':'' }}>Returned</option>
                                    </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a target="_blank" title="Download Invoice" href="{{route('order.invoice.download', $order->order_id)}}" class="pr-2"><i class="fa-solid fa-download fa-xl"></i></a>
                                    <a href="{{route('order.info', $order->order_id)}}"><i title="view" class="fas fa-eye fa-xl text-success"></i></a>
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
@endsection
@section('jscript')
    <script>
        $(document).ready( function () {
            $('#order_table').DataTable();
        } );
    </script>
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