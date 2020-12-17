<div class="modal-header">
    <h5 class="modal-title" id="exampleModalCenterTitle">Payment Details</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table table-bordered payment_details" style="word-break: break-all;">
        <thead>
            <th>Date</th>
            <th>Type</th>
            <th>Attachment</th>
            <th>Amount</th>
            <th>Book a Rider</th>
        </thead>
        <tbody>
        	<tr>
			    <td width="15%">{{$payment->payment_date}}</td>
			    <td width="25%">{{$payment->payment_type}}</td>
			    <td width="25%"><a target="_blank" href="{{ asset('storage/payments/'.$payment->id.'/'.$payment->attachment) }}">{{ $payment->attachment }}</a></td>
			    <td width="10%" class="text-right">{{number_format($payment->amount,2)}}</td>
			    <td width="25%">
			    	@if($payment->sales_header->sdd_booking_type == 1)
			    		<b>Customer</b>
			    	@else
			    		<b>ST PAULS Personnel</b>
			    	@endif
			    </td>
			</tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-primary btn-xs" onclick="approve_payment('{{$payment->id}}','APPROVE')">Approve</button>
	<button type="button" class="btn btn-danger btn-xs" onclick="approve_payment('{{$payment->id}}','REJECT')">Reject</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
</div>

