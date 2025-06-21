<html>
    <head>
        <style>
            body {
                font: 14px Arial, sans-serif;
                width: 90%;
                max-width: 95%;
                margin: 20px;
                padding: 20px;
                background:rgb(90, 115, 118);
                color: #333;
                line-height: 1.6;
                align:center;
            }
            h1 {
                font-size: 28px;
                color: #2c3e50;
                text-align: center;
                margin: 0 0 20px;
            }
            h2 {
                font-size: 22px;
                color: #2c3e50;
                text-align: center;
                margin: 0 0 20px;
            }
            p {
                margin: 10px 0;
                text-align: center;
            }
            .container {
                width: 100%;
                padding: 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,.05);
            }
            .order-details {
                width: 80%;
                background: #fff;
                border-radius: 8px;
                padding: 15px;
                margin: 20px 0;
                box-shadow: 0 1px 3px rgba(0,0,0,.1);
                text-align: left;
            }
            .order-details p {
                margin: 8px 0;
                display: flex;
            }
            .order-details p b {
                width: 120px;
                font-weight: 600;
                /* color: #2c3e50; */
            }
            table {
                width: 100%;
                border-collapse: collapse;
                background: #fff;
                margin: 20px 0;
                box-shadow: 0 1px 3px rgba(0,0,0,.1);
                border-radius: 8px;
                border: 1px solid #e5e7eb;
            }
            th, td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #e5e7eb;
                border-right: 1px solid #e5e7eb;
            }
            th {
                background: #f5f5f5;
                font-weight: 600;
                color: #2c3e50;
            }
            td {
                color: #555;
            }
            tr:last-child th, tr:last-child td {
                border-bottom: none;
            }
            th:last-child, td:last-child {
                border-right: none;
            }
            .total-row th, .total-row td {
                font-weight: 600;
                color: #2c3e50;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Service Booking</h1>
            <h2>Order Confirmation</h2>
            <div class="order-details">
                    <p><b>Customer name:</b> {{ $order->user->name }}</p>
                    <p><b>Customer email:</b> {{ $order->user->email }}</p>
                    <p><b>Order ID:</b> {{ $order->id }}</p>
                    <p><b>Order date:</b> {{ $order->created_at->toDayDateTimeString() }}</p>
                    <p><b>Order Status:</b> {{ $order->status }}</p>
                    <p><b>Total Items:</b> {{ $order->order_items->count() }}</p>
                    <p><b>Total Price:</b> {{ $order->total_price }} BDT</p>
                </div>
                
            <table>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Sub-Total</th>
                </tr>
                @foreach ($order->order_items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->service_name }}</td>
                        <td>{{ $item->schedule_time }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->service_price }} BDT</td>
                        <td>x{{ $item->quantity }}</td>
                        <td>{{ $item->sub_total_price }} BDT</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <th colspan="6">Total:</th>
                    <th>{{ $order->total_price }} BDT</th>
                </tr>
            </table>
            
            <p>This email was sent to <a href="#">{{ $order->user->email }}</a></p>
        </div>
    </body>
</html>