@php $total_qty = 0; $subtotal = 0; @endphp

@foreach($items as $item)
	@php
		$total_qty += $item->qty;
		$total = $item->product->price*$item->qty;
		$subtotal += $total;
	@endphp
	<tr>
		<td>{{ $item->product->code }}</td>
		<td>{{ str_limit(strip_tags($item->product->description), 80, $end ='...') }}</td>
		<td>{{ $item->qty }}</td>
		<td>{{ number_format($item->product->price,2) }}</td>
		<td>{{ number_format($total,2) }}</td>
	</tr>
@endforeach
	<tr style="font-weight:bold;">
        <td colspan="2">Sub total</td>
        <td>{{ number_format($total_qty,2) }}</td>
        <td>&nbsp;</td>
        <td>{{ number_format($subtotal,2) }}</td>
    </tr>
    <tr style="font-weight:bold;">
        <td colspan="4">Delivery Fee</td>                                 
        <td>{{ number_format($sales->delivery_fee_amount,2) }}</td>
    </tr>
    <tr style="font-weight:bold;">
        <td colspan="4">Grand total</td>
        <td>{{ number_format($subtotal+$sales->delivery_fee_amount,2) }}</td>
    </tr>