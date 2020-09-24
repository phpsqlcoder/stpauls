
@forelse($delivery as $d)
    <tr>
    	<td>{{$d->created_at}}</td>
        <td>{{$d->status}}</td>
        <td>{{$d->remarks}}</td>   
    </tr>
@empty
	<tr>
		<td colspan="3" class="tx-center">No deliveries found.</td>
	</tr>
@endforelse