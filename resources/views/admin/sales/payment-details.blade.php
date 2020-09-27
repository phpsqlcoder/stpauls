
<tr>
    <td class="pd-t-20">{{$payment->receipt_number}}</td>
    <td>{{$payment->payment_date}}</td>
    <td>{{$payment->payment_type}}</td>
    <td><a target="_blank" href="{{ asset('storage/payments/'.$payment->id.'/'.$payment->attachment) }}">View</a></td>
    <td class="text-right">{{number_format($payment->amount,2)}}</td>
    <td>{{$payment->status}}</td>
    <td>
    	<button type="button" class="btn btn-primary btn-xs" onclick="approve_payment('{{$payment->id}}','APPROVE')">Approve</button>
    	<button type="button" class="btn btn-danger btn-xs" onclick="approve_payment('{{$payment->id}}','REJECT')">Reject</button>
    </td>
</tr>
