<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ Setting::info()->company_name }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage').'/icons/'.Setting::getFaviconLogo()->website_favicon }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashforge.dashboard.css') }}">
    <style>
        /*html {
            border-top: 10px solid #b81600;
            
        }*/

        #print-footer {
            position: relative;
            bottom: 0;
            border-top:0px;
            border-right:0px;
            border-left: 0px; 
            border-bottom: 10px solid #b81600;
            background-color: #FFFFFF;
        }

        body {
            /*width: 30cm;  */
            /*height: 100% !important;*/
            height: 27cm;
            margin: 0 auto;
            padding-left: 25px;
            padding-right: 25px;
            color: #555555;
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

        table tfoot tr td:second-child {
            border: none;
        }

        @media screen {
            #print-footer { display: none; }
        }

        @media print {
            html { border-top: 10px solid #b81600; }

            #print-footer { 
                width: 100% !important;
                border-bottom: 10px solid #b81600;
                background-color: #FFFFFF;
                display: block;
                position: fixed;
                bottom: 0;
                left:0;
            }

            body { 
                padding-left: 50px !important;
                padding-right: 50px !important; 
                margin-top: 50px !important;
            }

            @page {
                border-top: 10px #b82e24 !important;
                margin:0 !important;
            }
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
            <div><a style="text-decoration: none;" href="mailto:{{$settings->email}}">{{$settings->email}}</a></div>
        </div>
    </header>

    <div class="clearfix mg-b-20">
        <div id="customer">
        </div>

        <div id="invoice-1">
            <small>Order Number</small>
            <h1 style="margin-top:-2px;color:#b82e24;">{{ $sales->order_number }}</h1>
        </div>
    </div>

    <div id="details" style="margin-bottom: 250px;">
        <div id="customer" class="col-sm-6 col-lg-6" >
            <label class="tx-sans tx-medium tx-spacing-1 tx-color-03">Billing Details</label>
            <ul class="list-unstyled lh-7">
                <li>
                    <span><h2 class="name">{{ $sales->customer_name }}</h2></span>
                </li>
                <li>
                    <span>{{$sales->customer_delivery_adress}}</span>
                </li>                            
                <li>
                    <span>{{$sales->customer_contact_number}}</span>
                </li>
                <li>
                    <span><a style="text-decoration: none;" href="mailto:{{ $sales->customer_main_details->email }}">{{ $sales->customer_main_details->email }}</a></span>
                </li>
                <li>&nbsp;</li>
                <li>Remarks : {{ $sales->remarks }} </li>
            </ul>
        </div>

        <div id="invoice" class="col-sm-6 col-lg-6">
            <label class="tx-sans tx-medium tx-spacing-1 tx-color-03">Order Details</label>
            <ul class="list-unstyled lh-7">
                <li class="d-flex justify-content-between">
                    <span>Order Date</span>
                    <span>{{ date('m/d/Y h:i A',strtotime($sales->created_at)) }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Payment Method</span>
                    <span>{{ \App\EcommerceModel\SalesHeader::payment_type($sales->id) }}</span>
                </li>                            
                <li class="d-flex justify-content-between">
                    <span>Payment Status</span>
                    <span class="tx-success tx-semibold tx-uppercase">{{ $sales->payment_status }}</span>
                </li>
                <hr style="margin: 0;">
                <li class="d-flex justify-content-between">
                    <span>Delivery Type</span>
                    <span class="tx-semibold tx-uppercase">{{ $sales->delivery_type }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Branch</span>
                    <span class="tx-semibold tx-uppercase">{{ $sales->branch ?? 'N/A' }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Delivery Status</span>
                    <span class="tx-success tx-semibold tx-uppercase">{{ $sales->delivery_status }}</span>
                </li>
            </ul>
        </div>
    </div>

    <table border="0" cellspacing="0" cellpadding="0">
        <thead style="background:#b81600;">
            <tr style="color:white;">
                <th width="30%"  style="text-align: left;">Item(s)</th>
                <th width="5%" style="text-align: right;">Weight (kg)</th>
                <th width="10%" style="text-align: right;">Price (₱)</th>
                <th width="5%" style="text-align: right;">Quantity</th>
                <th width="30%" style="text-align: right;">Total Weight (kg)</th>
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
                <td colspan="4" rowspan="3"></td>
                <td>Total Weight</td>
                <td class="text-right">{{ ($totalweight/1000) }} kg</td>
            </tr>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ number_format($subtotal,2) }}</td>
            </tr>
            <tr>
                <td>Shipping Fee</td>
                <td class="text-right">{{ number_format($sales->delivery_fee_amount,2) }}</td>
            </tr>
            <tr>
                <td colspan="4" rowspan="3">
                    <div class="col-sm-12 col-lg-8 order-2 order-sm-0 mg-t-40 mg-sm-t-0">
                        <div class="gap-30"></div>
                        <label class="tx-sans tx-10 tx-medium tx-spacing-1 tx-color-03">Other Instructions</label>
                        <p>{{ $sales->other_instruction ?? 'N/A' }}</p>
                    </div>
                </td>
                <td>Service Fee</td>
                <td class="text-right">{{ number_format($sales->service_fee,2) }}</td>
            </tr>
            <tr>
                <td class="text-danger">Less: Loyalty Discount({{$sales->discount_percentage}}%)</td>
                <td class="text-right text-danger">{{ number_format($sales->discount_amount,2) }}</td>
            </tr>
            <tr>
                <td><h5 class="tx-success">Grand Total</h5></td>
                <td class="text-right"><h5>{{ number_format($sales->net_amount,2)}}</h5></td>
            </tr>
        </tfoot>
    </table>

    <div class="row col-sm-12 mg-b-100">
        <div class="col-sm-6 col-lg-6">
            <div><strong>Received By:</strong></div>
            <center>
                <div>&nbsp;</div>
                <div>____________________________________</div>
                <div>Signature over printed name (MM/DD/YYYY)</div>
            </center>
            
        </div>
        <div class="col-sm-6 col-lg-6">
            <div><strong>Delivered By:</strong></div>
            <center>
                <div>&nbsp;</div>
                <div>____________________________________</div>
                <div>Signature over printed name (MM/DD/YYYY)</div>
            </center>
        </div>
    </div>

    <div id="print-footer">
        <div class="row col-sm-12 pd-0 mg-0">
            <div class="col-sm-8 mg-t-70" >
                <span class="tx-lowercase"><i class="fa fa-facebook-square"></i> &nbsp;www.stpauls.ph</span>&nbsp;&nbsp;
                <span><i class="fa fa-facebook-square"></i> &nbsp;St Pauls Online</span>&nbsp;&nbsp;
                <span class="tx-lowercase"><i class="fa fa-facebook-square"></i> &nbsp;@stpaulsph</span>
                
            </div>
            <div class="col-sm-4 d-flex justify-content-end">
                <img src="{{ url('/') }}/theme/stpaul/images/others/Doing all for the gospel.png" width="230px">
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            window.print();
        })
    </script>
</body>
</html>