Dear {{ $customer->firstname }},


Good day!

Please be informed that your order has been approved.


Please prepare cash with the exact amount for payment.

If you are unable to receive the order, please authorize someone who can receive and pay the order on your behalf.


For any inquiry or comments, please contact us at {{ $setting->email }}.

Thank you.


Regards,
{{ $setting->company_name }}
Support Team



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
