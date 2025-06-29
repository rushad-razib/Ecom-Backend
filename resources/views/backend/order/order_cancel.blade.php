@extends('layouts.admin')
@section('content')
@can('order_cancel')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>Cancel Message</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('cancel.order', $order->id)}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="message">Message</label>
                                <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Cancel Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <h3>You do not have permission to view this page</h3>
@endcan
@endsection