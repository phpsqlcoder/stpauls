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
                Dear Admin,
                <br>
                <br>
                Good Day!
                <br>
                <br>
                You have received a payment from <strong>{{ $sales->order_number }}</strong> for validation.
                <br>
                <br>
                @php
                    $payment = \App\EcommerceModel\SalesPayment::where('sales_header_id',$sales->id)->first();
                @endphp

                Payment details: 
                <br><br>
                Date of payment: {{ $payment->payment_date }}<br />
                Mode of Payment: {{ $payment->payment_type }}<br />
                Payment Attachment: <a target="_blank" href="{{ asset('storage').'/payments/'.$payment->id.'/'.$payment->attachment }} ">{{ $payment->attachment }}</a>

                <br />
                <br />
                <strong>Order Details Below:</strong><br />
                @include('mail.customer.order-details')
                <br>
                <br>
                <p>
                    For verification and approval of the payment, you may check the payment details here <a href="{{ route('sales-transaction.view',$sales->id) }}">Order Details</a>.
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