

@forelse($products as $p)
	<a class="dropdown-item" href="{{ route('product.front.show',$p->slug)}}">
        <div class="row">
            <div class="col-3">
                <img src="{{ asset('storage/products/'.$p->photoPrimary) }}" alt="">
            </div>
            <div class="col-9">
                <h2>{{ $p->name }}</h2>
                @if(\App\EcommerceModel\Product::onsale_checker($p->id) > 0)
                	<p class="old-price">Php {{ number_format($p->price,2) }}</p>
                	<p class="price">Php {{ $p->discountedprice }}</p>
                @else
                    @if($p->discount > 0)
                    	<p class="old-price">Php {{ number_format($p->price,2) }}</p>
                		<p class="price">Php {{ number_format($p->price-$p->discount,2) }}</p>
                    @else
                    	<p class="price">Php {{ $p->PriceWithCurrency }}</p>
                   	@endif
                @endif
            </div>
        </div>
    </a>
@empty
    <center><a class="dropdown-item" href="#">We couldn't find a <b>{{$keyword}}</b> for sale</a></center>
@endforelse