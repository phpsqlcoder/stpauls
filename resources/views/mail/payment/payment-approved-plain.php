Dear {{ $customer->firstname }},


Good day!

Please be informed that your payment for order # : <strong>{{ $payment->sales_header->order_number }}</strong> has been approved.


For any inquiry or comments, please contact us at {{ $setting->email }}.

Thank you.


Regards,
{{ $setting->company_name }}
Support Team



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
