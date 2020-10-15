<ul class="rd-navbar-nav">
    @php
	    $parentCategories = \App\EcommerceModel\ProductCategory::where('parent_id',0)->where('status', 'PUBLISHED')->get();
	@endphp

	@foreach($parentCategories as $category)
	  	<li>
	  		<a href="{{ route('product.front.list',$category->slug) }}">{{$category->name}}</a>
	 		@if(count($category->child_categories))
	    		@include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.product-category-item',['subcategories' => $category->child_categories])
	  		@endif
	 	</li>
	@endforeach
</ul>