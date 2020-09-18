Dear {{ $customer->firstname }},


Good day!

Please be informed that your reactivation request has been disapproved.

We apologize for the inconvenience this has caused you.

For any inquiry or comments, please contact us at {{ $setting->email }}.

Thank you.


Regards,
{{ $setting->company_name }}
Support Team



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
