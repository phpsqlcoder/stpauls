<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <style>

        #customer {
            padding-left: 8px;
            border-left: 6px solid #b82e24;
            float: left;
        }

        #invoice {
            float: right;
            padding-right: 8px;
            border-right: 6px solid #b82e24;
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

        table tfoot tr:first-child td {
            border-top: none; 
        }

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1.4em;
            border-top: 1px solid #57B223; 

        }

        table tfoot tr td:first-child {
            border: none;
        }
    </style>
    </head>
    <body style="background:#FFFFFF;font-family:arial;">
    <p>&nbsp;</p>
    <table style="width:850px;margin:auto;background:#fff;border:1px solid #dddddd;padding:1em;-webkit-border-radius:5px;border-radius:5px;font-size:12px;">
        <tr>
            <td>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage').'/logos/'.$setting->company_logo }}" alt="ST PAUL" width="175" />
                </a>
            </td>
        </tr>
        <tr>
            <td>
                Customer: {{ $sales->customer_name }}
                <br>
                <br>
                Dear Admin,
                <br>
                <br>
                Good Day!
                <br>
                <br>
                You have received a COD request from order #<strong>{{ $sales->order_number }}</strong> for your approval.
                <br />
                <br />
                <strong>Order Details Below:</strong><br />

                <div style="overflow:auto;margin-bottom:20px;">
                    <div>
                    </div>

                    <div id="invoice-1">
                        <small>Order Number</small><br/><span style="color:#b82e24;font-size: 2rem;">{{ $sales->order_number }}</span>
                    </div>
                </div>

                <div style="margin-bottom: 250px;">
                    <div id="customer" style="flex: 0 0 40%;max-width: 100%;">
                        <label style="display: inline-block;margin-bottom: 0.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Inter UI', Roboto, sans-serif;font-weight: 500;letter-spacing: 0.5px;color: #8392a5;">Billing Details</label>
                        <h2 class="name">{{ $sales->customer_name }}</h2>
                        {{$sales->customer_delivery_adress}}<br/>
                        {{$sales->customer_contact_number}}<br/>
                        <a href="mailto:{{ $sales->customer_main_details->email }}">{{ $sales->customer_main_details->email }}</a><br/><br/>
                        <p class="mg-b-10">Remarks :</p>
                        <ul class="list-unstyled">
                            @if($sales->remarks != '')
                                <li>{{ $sales->remarks }}</li>
                            @endif
                        </ul>
                        <p>Other Instructions : {{ $sales->other_instruction ?? 'N/A' }}</p>

                        @if($sales->sdd_booking_type == 1)
                        <p>Courier Name : {{ $sales->courier_name }}</p>
                        <p>Rider Name : {{ $sales->rider_name }}</p>
                        <p>Contact # : {{ $sales->rider_contact_no }}</p>
                        <p>Plate # : {{ $sales->rider_plate_no }}</p>
                        <p>Rider Tracker Link : {{ $sales->rider_link_tracker }}</p>
                        @endif
                    </div>

                    <div id="invoice" style="flex: 0 0 60%;max-width: 100%;">
                        <label style="display: inline-block;margin-bottom: 0.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Inter UI', Roboto, sans-serif;font-weight: 500;letter-spacing: 0.5px;color: #8392a5;">Order Details</label><br/>
                        Order Date <span style="float: right;">{{ date('m/d/Y h:i A',strtotime($sales->created_at)) }}</span><br/>
                        Payment Method <span style="float: right;">{{ \App\EcommerceModel\SalesHeader::payment_type($sales->id) }}</span><br/>
                        Payment Status <span style="float: right;color:#10b759;font-weight: 600;">{{ $sales->payment_status }}</span><br/>
                        <hr>
                        Delivery Type <span style="float: right;text-transform: uppercase;">
                                        @if($sales->delivery_type == 'Same Day Delivery')
                                            Book Your Own Rider
                                        @else
                                            {{ $sales->delivery_type }}
                                        @endif
                                      </span><br/>

                        @if($sales->delivery_type == 'Store Pick Up')
                        Branch <span style="float: right;">{{ $sales->branch }}</span><br/>
                        Pick-up Date <span style="float: right;">{{ $sales->pickup_date }}</span><br/>
                        @endif

                        Delivery Status <span style="float: right;color:#10b759;font-weight: 600;text-transform: uppercase;">{{ $sales->delivery_status }}</span>
                        &nbsp;<br>
                        &nbsp;<br>
                        &nbsp;<br>
                        &nbsp;<br>
                        &nbsp;<br>
                        @if($sales->delivery_type == 'Store Pick Up')
                        &nbsp;</br>
                        &nbsp;<br>
                        @endif
                        @if($sales->sdd_booking_type == 1)
                        <span>&nbsp;</span>
                        <span>&nbsp;</span>
                        <span>&nbsp;</span>
                        <span>&nbsp;</span>
                        @endif
                    </div>
                </div>

                <table border="0" cellspacing="0" cellpadding="0">
                    <thead style="background:#b81600;">
                        <tr style="color:white;">
                            <th width="30%" style="text-align: left;">Item(s)</th>
                            <th width="10%" style="text-align: right;">Weight (kg)</th>
                            <th width="10%" style="text-align: right;">Price (₱)</th>
                            <th width="10%" style="text-align: right;">Quantity</th>
                            <th width="20%" style="text-align: right;">Total Weight (kg)</th>
                            <th width="20%" style="text-align: right;">Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; $weight = 0; $total = 0; $totalweight = 0; @endphp
                        @foreach($sales->items as $item)
                        @php
                            $weight   = $item->product->weight*$item->qty;
                            $total    = $item->price*$item->qty;
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
                            <td class="text-right">{{ ($totalweight/1000) }} kg</td>
                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td>Sub-Total</td>
                            <td class="text-right">{{ number_format($subtotal,2) }}</td>
                        </tr>
                        @if($sales->discount_percentage > 0)
                        <tr>
                            <td colspan="4"></td>
                            <td class="text-danger">LESS: Loyalty Discount({{$sales->discount_percentage}}%)</td>
                            <td class="text-right text-danger">{{ number_format($sales->discount_amount,2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4"></td>
                            <td>ADD: Shipping Fee</td>
                            <td class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
                        </tr>
                        @if($sales->service_fee > 0)
                        <tr>
                            <td colspan="4"></td>
                            <td>ADD: Service Fee</td>
                            <td class="text-right">{{ number_format($sales->service_fee,2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4"></td>
                            <td><h5 class="text-success"><b>TOTAL DUE</b></h5></td>
                            <td class="text-right"><h5>{{ number_format($sales->net_amount,2)}}</h5></td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <br>
                <p>
                    For approval of the order, you may check the order details here <a href="{{ route('sales-transaction.view',$sales->id) }}">Order Details</a>.
                </p>
                <br>
                <br>
                Thank you.
                <br>
                <br>
                Regards
            </td>
        </tr>
    </table>
    </body>
</html>