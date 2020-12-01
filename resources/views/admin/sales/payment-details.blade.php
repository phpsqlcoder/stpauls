
<tr>
    <td width="15%">{{$payment->payment_date}}</td>
    <td width="15%">{{$payment->payment_type}}</td>
    <td width="20%"><a target="_blank" href="{{ asset('storage/payments/'.$payment->id.'/'.$payment->attachment) }}">{{ $payment->attachment }}</a></td>
    <td width="10%" class="text-right">{{number_format($payment->amount,2)}}</td>
    <td width="15%">
    	@if($payment->sales_header->sdd_booking_type == 1)
    		<b>Customer</b>
    	@else
    		<b>ST PAULS Personnel</b>
    	@endif
    </td>
    <td width="25%">
    	<button type="button" class="btn btn-primary btn-xs" onclick="approve_payment('{{$payment->id}}','APPROVE')">Approve</button>
    	<button type="button" class="btn btn-danger btn-xs" onclick="approve_payment('{{$payment->id}}','REJECT')">Reject</button>
    </td>
</tr>
