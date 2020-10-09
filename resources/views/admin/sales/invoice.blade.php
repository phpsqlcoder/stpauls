<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ Setting::info()->company_name }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 21cm;  
            height: 27cm; 
            margin: 0 auto; 
            color: #555555;
            background: #FFFFFF; 
            font-family: "IBM Plex Sans", sans-serif; 
            font-size: 14px; 
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #company {
            float: right;
            text-align: right;
        }

        #details {
            margin-bottom: 50px;
        }

        #customer {
            padding-left: 8px;
            border-left: 6px solid #b82e24;
            float: left;
        }

        #invoice {
            float: right;
            padding-right: 8px;
            border-right: 6px solid #b82e24;
            text-align: right;
        }

        #invoice-1 {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 10px 0;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th {
            padding: 20px;
            background: #b81600;
            border-bottom: 1px solid #FFFFFF;
        }

        table td {
            padding: 15px;
            background: #EEEEEE;
            border-bottom: 1px solid #FFFFFF;
        }

        table tfoot td {
            padding: 10px;
            background: #EEEEEE;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;        
            font-weight: normal;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot tr {
            text-align: right;
        }
        table tfoot tr td:first-child {
            border: none;
        }

        .row {
            display: flex;
        }

        .signatories {
            flex: 50%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <header class="clearfix">
        <div id="logo">
            <img src="{{ asset('storage/logos/'.Setting::getFaviconLogo()->company_logo) }}" alt="StPaul" width="200" height="70" />
        </div>
        <div id="company">
            <h2 class="name">{{ $settings->company_name }}</h2>
            <div>{{ $settings->company_address }}</div>
            <div>{{ $settings->mobile_no }} | {{ $settings->tel_no }}</div>
            <div><a href="mailto:{{$settings->email}}">{{$settings->email}}</a></div>
        </div>
    </div>
</header>
<main>
    <div class="clearfix">
        <div id="customer">
        </div>

        <div id="invoice-1">
            <small>Order Number</small>
            <h1 style="margin-top:-2px;color:#b82e24;">{{ $sales->order_number }}</h1>
        </div>
    </div>

    <div id="details" class="clearfix">
        <div id="customer">
            <div>Billing Information:</div>
            <h2 class="name">{{ $sales->customer_name }}</h2>
            <div>{{ $sales->customer_delivery_adress }}</div>
            <div><a href="mailto:{{ $sales->customer_main_details->email }}">{{ $sales->customer_main_details->email }}</a></div>
        </div>

        <div id="invoice">
            <div>Order Date: {{ date('m/d/Y h:i A',strtotime($sales->created_at)) }}</div>
            <div>Payment Method: {{ \App\EcommerceModel\SalesHeader::payment_type($sales->id) }}</div>
            <div>Payment Status: {{ $sales->payment_status }}</div>
            <div>Delivery Status: {{ $sales->delivery_status }}</div>
        </div>
    </div>
    <table border="0" cellspacing="0" cellpadding="0">
        <thead style="background:#b81600;">
            <tr style="color:white;">
                <th width="30%"  style="text-align: left;">Item(s)</th>
                <th width="" style="text-align: right;">Weight (kg)</th>
                <th style="text-align: right;">Price (₱)</th>
                <th style="text-align: right;">Quantity</th>
                <th style="text-align: right;">Total Weight (kg)</th>
                <th style="text-align: right;">Total (₱)</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; $weight = 0; $total = 0; $totalweight = 0; @endphp
            @foreach($sales->items as $item)
            @php
                $weight   += $item->product->weight*$item->qty;
                $total    += $item->price*$item->qty;
                $subtotal += $total;
                $totalweight += $weight;
            @endphp
            <tr>
                <td>{{ $item->product_name }}</td>
                <td style="text-align: right;">{{ ($item->product->weight/1000) }}</td>
                <td style="text-align: right;">{{ number_format($item->price,2) }}</td>
                <td style="text-align: right;">{{ $item->qty }}</td>
                <td style="text-align: right;">{{ ($weight/1000) }}</td>
                <td style="text-align: right;">{{ number_format($total,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"></td>
                <td>Total Weight</td>
                <td>{{ ($totalweight/1000) }} kg</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td>Subtotal</td>
                <td>{{ number_format($subtotal,2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td>Loyalty Discount</td>
                <td>{{ number_format($sales->discount_amount,0) }}%</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td>Shipping Fee</td>
                <td>{{ number_format($sales->delivery_fee_amount,2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td>Service Fee</td>
                <td>{{ number_format($sales->service_fee,2) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td><strong>Grand Total</strong></td>
                <td>{{ number_format($sales->net_amount,2)}}</td>
            </tr>
        </tfoot>
    </table>

    <div class="row" style="margin-top: 60px;padding-left: 30px;">
        <div class="signatories">
            <div><strong>Received By:</strong></div>
            <center>
                <div>&nbsp;</div>
                <div>____________________________________</div>
                <div>Signature over printed name (MM/DD/YYYY)</div>
            </center>
            
        </div>
        <div class="signatories">
            <div><strong>Delivered By:</strong></div>
            <center>
                <div>&nbsp;</div>
                <div>____________________________________</div>
                <div>Signature over printed name (MM/DD/YYYY)</div>
            </center>
        </div>
    </div>
</main>

<script>
    window.addEventListener('load', function() {
        window.print();
    })
</script>
</body>
</html>