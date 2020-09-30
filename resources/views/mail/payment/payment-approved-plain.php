Dear {{ $customer->firstname }},


Good day!

Please be informed that your payment for Order # : <strong>{{ $payment->sales_header->order_number }}</strong> has been approved.

@if($payment->sales_header->branch != '')
You may pick up your order at {{ $payment->sales_header->branch }} branch.
@endif

To check the status of your order, you may click on <strong>My Orders</strong> in your account.


For any inquiry or comments, please contact us at {{ $setting->email }}.

Thank you.


Regards,
{{ $setting->company_name }}
Support Team



{{ $setting->company_name }}
{{ $setting->company_address }}
{{ $setting->tel_no }} | {{ $setting->mobile_no }}

{{ url('/') }}
