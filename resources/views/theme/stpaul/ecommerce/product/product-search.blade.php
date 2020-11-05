

@forelse($products as $p)
    <a class="dropdown-item" href="{{ route('product.front.show',$p->slug)}}">
        <div class="prodImg"><img class="product-img" src="{{ asset('storage/products/'.$p->photoPrimary) }}" alt="" height="120" /></div>{{ $p->name }}</a>
@empty
    <center><a class="dropdown-item" href="#">We couldn't find a <b>{{$keyword}}</b> for sale</a></center>
@endforelse