@extends('layouts.admin')
@section('content')
@can('order_cancel')
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Order Cancellation</h3>
                    </div>
                    <div class="card-body">
                        <h4>Order Information</h4>
                        <hr>
                        <table class="table table-striped">
                            <tr>
                                <th>Order ID</th>
                                <td>{{$cancel_reason->order_id}}</td>
                            </tr>
                            <tr>
                                <th>Ordered Date</th>
                                <td>{{$order->created_at->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <th>Cancel Request Date</th>
                                <td>{{$cancel_reason->created_at->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <th>Cancel reason</th>
                                <td>{{$cancel_reason->reason}}</td>
                            </tr>
                            <tr>
                                <th>Take Action</th>
                                <td class="d-flex gap-1">
                                    <a href="{{route('cancel.manage', $order->id)}}" class="btn btn-primary">Accept</a>
                                    <a href="{{route('cancel.deny', $cancel_reason->id)}}" class="btn btn-danger">Reject</a>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <h4>Customer Information</h4>
                        <hr>
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <td>{{$customer_info->fname}} {{$customer_info->lname}}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{$customer_info->address == ''?'Null':$customer_info->address}}</td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                @if($order->payment_method == 1)
                                    <td>Cash on delivery</td>
                                @elseif($order->payment_method == 2)
                                    <td>SSL</td>
                                @else
                                    <td>Stripe</td>
                                @endif
                            </tr>
                        </table>
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Ordered Products</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Name</th>
                                <th>quantity</th>
                                <th>Price(&#2547;)</th>
                            </tr>
                            @foreach ($ordered_products as $product)
                                <tr>
                                    <td class="text-wrap">{{$product->rel_to_product->name}}</td>
                                    <td>{{$product->quantity}}</td>
                                    <td>{{$product->price * $product->quantity}}</td>
                                </tr>
                            @endforeach
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