
<tr>
	<input type="hidden" name="payment_id" value="{{$payment->id}}">
    <td class="pd-t-20">{{$payment->receipt_number}}</td>
    <td>{{$payment->payment_date}}</td>
    <td>{{$payment->payment_type}}</td>
    <td><a target="_blank" href="{{ asset('storage/payments/'.$payment->id.'/'.$payment->attachment) }}">View</a></td>
    <td class="text-right">{{number_format($payment->amount,2)}}</td>
    <td>{{$payment->status}}</td>
    <td>
    	<button class="btn btn-primary btn-xs" onclick="approve_payment()">Approve</button>
    	<button class="btn btn-danger btn-xs">Cancel</button>
    </td>
</tr>
