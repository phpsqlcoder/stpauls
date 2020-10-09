<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>32321</title>
    </head>
    <body style="background:#f4f4f4;font-family:arial;">
    <p>&nbsp;</p>
    <table style="width:580px;margin:auto;background:#fff;border:1px solid #dddddd;padding:1em;-webkit-border-radius:5px;border-radius:5px;font-size:12px;">
        <tr>
            <td>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage').'/logos/'.$setting->company_logo }}" alt="ST PAUL" width="175" />
                </a>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Dear {{ $sales->customer_name }},

                @php
                    $qrypayment    = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sales->id);
                    $countpayment  = $qrypayment->count();

                    if($countpayment > 0){
                        $payment = $qrypayment->first();

                        $paidamount = $payment->amount;
                    } else {
                        $paidamount = 0;
                    }

                    $payment       = $qrypayment->first();

                    $content    = $template->content;
                    $keywords   = ['{order_number}', '{shippingfee}', '{paid_amount}', '{remarks}'];

                    $variables  = [$sales->order_number, number_format($sales->delivery_fee_amount,2), number_format($paidamount,2), $sales->remarks];

                    $newContent = str_replace($keywords,$variables,$content);
                @endphp

                    {!! $newContent !!}

                <br />
                <br />
                <strong>Your shipping details are as follows:</strong><br />
                <table width="100%">
                    <thead>
                        <th width="20%">&nbsp;</th>
                        <th width="35%">&nbsp;</th>
                        <th width="20%">&nbsp;</th>
                        <th width="25%">&nbsp;</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Order Number :</td>
                            <td>{{ $sales->order_number }}</td>
                            <td>Order Date :</td>
                            <td>{{ date('Y-m-d h:i A',strtotime($sales->created_at)) }}</td>
                        </tr>
                        <tr>
                            <td>Customer Name :</td>
                            <td>{{ $sales->customer_name }}</td>
                            <td>Payment Status :</td>
                            <td>{{ $sales->payment_status }}</td>
                        </tr>
                        <tr>
                            <td>Billing Address :</td>
                            <td>{{ $sales->customer_delivery_adress }}</td>
                            <td>Delivery Status :</td>
                            <td>{{ $sales->delivery_status }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Payment Method :</td>
                            <td>
                                @if($sales->payment_option == 0)
                                    Cash
                                @else
                                    $sales->payment_method
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Remarks :</td>
                            <td>
                                {{ $sales->remarks }} 
                            </td>
                            <td>Shipping Type :</td>
                            <td>{{ $sales->delivery_type }}</td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>

                <table width="100%">
                    <thead style="background:#b81600 ;">
                        <tr style="color:white;">
                            <th style="padding:.5em;">Item(s)</th>
                            <th style="padding:.5em; text-align:center;">Weight (kg)</th>
                            <th style="padding:.5em; text-align:center;">Price (₱)</th>
                            <th style="padding:.5em; text-align:center;">Quantity</th>
                            <th style="padding:.5em; text-align:center;">Total Weight (kg)</th>
                            <th style="padding:.5em; text-align:center;">Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $weight = 0;
                            $totalamount = 0;
                            $subtotal = 0;
                            $totalweight = 0;
                        @endphp

                        @foreach($sales->items as $item)
                        @php
                            $weight += $item->product->weight*$item->qty;
                            $totalamount += $item->price*$item->qty;
                            $subtotal += $totalamount;
                            $totalweight += $weight;
                        @endphp
                        <tr>
                            <td style="padding:.5em;">
                                <div style="width:60%;">
                                    {{ $item->product->name }}
                                </div>
                            </td>
                            <td style="padding:.5em; text-align:center;">{{ ($item->product->weight/1000) }}</td>
                            <td style="padding:.5em; text-align:center;">{{ number_format($item->price,2) }}</td>
                            <td style="padding:.5em; text-align:center;">{{ $item->qty }}</td>
                            <td style="padding:.5em; text-align:center;">{{ ($weight/1000) }}</td>
                            <td style="padding:.5em; text-align:right;">{{ number_format($totalamount,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><strong>Total Weight</strong></td>
                            <td></td>
                            <td style="text-align:right;">{{ ($totalweight/1000) }} kg</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><strong>Subtotal</strong></td>
                            <td></td>
                            <td style="text-align:right;">₱ {{ number_format($subtotal,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><strong>Loyalty Discount</strong></td>
                            <td></td>
                            <td style="text-align:right;">{{ number_format($sales->discount_amount,0) }}%</td>
                        </tr>

                        <tr>
                            <td colspan="4" style="text-align:right;"><strong>Shipping Rate</strong></td>
                            <td></td>
                            <td style="text-align:right;">₱ {{ number_format($sales->delivery_fee_amount,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><strong>Service Fee</strong></td>
                            <td></td>
                            <td style="text-align:right;">₱ {{ number_format($sales->service_fee,2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right;"><strong>Grand Total</strong></td>
                            <td></td>
                            <td style="text-align:right;">₱ {{number_format($sales->net_amount,2) }}</td>
                        </tr>
                    </tfoot>
                </table>
                <br />
                <br />
                
                Respectfully yours,<br />
                Your {{ $setting->company_name }} family
                <br />
                <br />
                <small style="color:red">This is an auto-generated registration notification, please do not reply. This communication is intended solely for the use of the addressee and authorized recipients. It may contain confidential or legally privileged information and is subject to the conditions in <a href="{{ url('/') }}">{{ url('/') }}</a></small>
            </td>
        </tr>
    </table>
    <p style="text-align:center;font-size:11px;color:#999999;">Copyright &copy; {{ date('Y') }} <a href="">{{ $setting->company_name }}</a>. All rights reserved.</p>
    <p>&nbsp;</p>
    </body>
</html>