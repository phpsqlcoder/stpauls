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
    <table style="width:750px;margin:auto;background:#fff;border:1px solid #dddddd;padding:1em;-webkit-border-radius:5px;border-radius:5px;font-size:12px;">
        <tr>
            <td>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage').'/logos/'.$setting->company_logo }}" alt="ST PAUL" width="175" />
                </a>
            </td>
        </tr>
        <tr>
            <td>
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
                    $keywords   = ['{order_number}', '{shippingfee}', '{paid_amount}', '{remarks}', '{net_amount}', '{delivery_status}'];

                    $variables  = [$sales->order_number, number_format($sales->delivery_fee_amount,2), number_format($paidamount,2), $sales->remarks, number_format($sales->net_amount,2), $sales->delivery_status];

                    $newContent = str_replace($keywords,$variables,$content);
                @endphp

                    {!! $newContent !!}

                <br />
                <br />
                <strong>Your order details are as follows:</strong><br />
                @include('mail.customer.order-details')
                <br />
                <br />
                <small style="color:red">This is an auto-generated notification, please do not reply. This communication is intended solely for the use of the addressee and authorized recipients. It may contain confidential or legally privileged information and is subject to the conditions in <a href="{{ url('/') }}">{{ url('/') }}</a></small>
            </td>
        </tr>
    </table>
    <p style="text-align:center;font-size:11px;color:#999999;">Copyright &copy; {{ date('Y') }} <a href="">{{ $setting->company_name }}</a>. All rights reserved.</p>
    <p>&nbsp;</p>
    </body>
</html>