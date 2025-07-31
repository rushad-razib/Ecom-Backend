<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px 0;
            border-radius: 5px;
        }
        .header h1 {
            margin: 0;
        }
        .company-info, .customer-info {
            margin-bottom: 20px;
        }
        .company-info p, .customer-info p {
            margin: 5px 0;
            color: #333;
        }
        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-details th, .invoice-details td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .invoice-details th {
            background: #FAAA0C;
            color: #fff;
        }
        .totals {
            width: 100%;
        }
        .totals td {
            padding: 10px;
            text-align: right;
            color: #333;
        }
        .totals .label {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .footer a{
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid gray;
            background: #233D50;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: all .1s ease-in-out;
        }
        .footer a{
            text-decoration: none;
        }
        .footer a:hover{
            background: #FAAA0C;
            border: transparent;
            text-decoration: none;
        }
        .info_section{
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            justify-content: space-between;
            align-items: start;
        }
        .right_section{
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            row-gap: 8px;
        }
        .right_section .order_info p{
            color: #333;
            line-height: 15px;
        }
        .left_section{
            display: flex;
            flex-direction: column;
            row-gap: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
        <div class="info_section">
            <div class="left_section">
                <div class="company-info">
                    <img src="https://i.postimg.cc/ZqkFShWT/logo-2.png" alt="">
                    <p>123 Business St, City, Country</p>
                    <p>Email: info@themart.com</p>
                    <p>Phone: +123 456 7890</p>
                </div>
            </div>
            <br>
            @php
                $bill = App\Models\Billing::where('order_id', $order_id)->first();
                $orders = App\Models\Order::where('order_id', $order_id)->first();
            @endphp
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
                <tr>
                    <td style="padding-bottom: 10px;">
                        <p><strong>Order ID: {{$order_id}}</strong></p>
                        <p><strong>Invoice Date: {{$bill->created_at->format('d-m-Y')}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Bill To:</strong></p>
                        <p>{{$orders->rel_to_customer->name}}</p>
                        <p>{{$bill->address}}</p>
                        <p>Email: {{$bill->email}}</p>
                        <p>Phone: {{$bill->phone}}</p>
                    </td>
                </tr>
            </table>
        </div>
        <table class="invoice-details">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            @php
                $sub = 0;
            @endphp
            @foreach (\App\Models\OrderProduct::where('order_id', $order_id)->get() as $ordered_items)
            <tr>
                <td>{{$ordered_items->rel_to_product->name}}</td>
                <td>{{$ordered_items->quantity}}</td>
                <td>&#2547;{{$ordered_items->price}}</td>
                <td>&#2547;{{$ordered_items->price * $ordered_items->quantity}}</td>
            </tr>
            @endforeach
        </table>
        
        <table class="totals">
            <tr>
                <td class="label">Subtotal:</td>
                <td>&#2547;{{$orders->subtotal}}</td>
            </tr>
            <tr>
                <td class="label">Coupon Discount:</td>
                <td>- &#2547;{{$orders->discount}}</td>
            </tr>
            <tr>
                <td class="label">Charge:</td>
                <td>&#2547;{{$orders->charge}}</td>
            </tr>
            <tr>
                <td class="label"><strong>Grand Total:</strong></td>
                <td><strong>&#2547;{{$orders->total}}</strong></td>
            </tr>
        </table>
        
        <div class="footer">
            <a target="_blank" href="">View Order Status</a>
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
