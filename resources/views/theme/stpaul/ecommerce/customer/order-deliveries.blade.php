@forelse($deliveries as $delivery)
	<tr>
		<td>{{ date('Y-m-d h:i A',strtotime($delivery->created_at)) }}</td>
		<td>{{ $delivery->status }}</td>
		<td>{!! $delivery->remarks !!}</td>
	</tr>
@empty
	<tr><td colspan="3" class="text-center">No deliveries yet.</td></tr>
@endforelse