@foreach($subcategories as $subcategory)
	<option @if(count($subcategory->child_categories)) style="font-weight: bold;" @endif value="{{ $subcategory->id }}">
		@for ($i = 1; $i <= $subcategory->categorylevel; $i++)
	        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    @endfor
		{{ $subcategory->name }}
	</option>
	
	@if(count($parentCategory->child_categories))
		@include('admin.products.select-subcategories',['subcategories' => $subcategory->child_categories])
	@endif
@endforeach