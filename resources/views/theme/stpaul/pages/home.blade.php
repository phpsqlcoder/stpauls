@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')
	<main>
        <!-- Home Main Body Area -->
        <section id="home-body">
            <div class="container">

                <!-- Our Products Navigation Tabs -->
                 <div class="category-flex-1">
                    <h2 class="category-title"><span>Our Products</span></h2>
                    <div class="category-nav-1">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-book-tab" data-toggle="pill" href="#pills-book" role="tab" aria-controls="pills-home" aria-selected="true">Books</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-bible-tab" data-toggle="pill" href="#pills-bible" role="tab" aria-controls="pills-profile" aria-selected="false">Bibles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-devotional-tab" data-toggle="pill" href="#pills-devotional" role="tab" aria-controls="pills-contact" aria-selected="false">Devotional</a>
                        </li>
                    </ul>
                    <div class="product-nav">
                        <a href="" class="product-prev"><span class="lnr lnr-arrow-left"></span></a>
                        <a href="" class="product-next"><span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
                 </div>
                
                <!-- END Our Products Navigation Tabs -->

                <div class="gap-40"></div>

                <!-- Our Products Tab Contents -->
                <div class="tab-content" id="pills-tabContent">

                    <!-- Our Products Books Tab Content -->
                    <div class="tab-pane fade show active" id="pills-book" role="tabpanel" aria-labelledby="pills-book-tab">
                        <div id="book" class="slick-slider">
                            @foreach(\App\EcommerceModel\Product::products_cat(3) as $book)
                            <div class="product-link">
                                <div class="product-card">
                                    @if($book->discount > 0)
                                        <div class="product-discount">₱ {{ $book->discount }} LESS</div>
                                    @endif
                                    <a href="{{ route('product.front.show',$book->slug)}}">
                                        <img src="{{ asset('storage/products/'.$book->photoPrimary) }}" alt="" />
                                        @if($book->discount > 0)
                                        <h3 class="product-price">
                                            <div class="old" style="font-size:15px;">Php {{ number_format($book->price,2) }}</div>
                                            Php {{ number_format($book->price-$book->discount,2) }}
                                        </h3>
                                        @else
                                        <h3 class="product-price"><br>Php {{ number_format($book->price,2) }}</h3>
                                        @endif
                                    </a>
                                    <p class="product-title">{{ $book->name }}</p> 
                                    @if($book->inventory > 0)
                                        <button type="button" onclick="add_to_cart('{{$book->id}}');" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                            <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                        </button>
                                    @else
                                        <button type="button" class="btn out-of-stock">
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- END Our Products Books Tab Content -->

                    <!-- Our Products Bibles Tab Content -->
                    <div class="tab-pane fade" id="pills-bible" role="tabpanel" aria-labelledby="pills-bible-tab">
                        <div id="bible" class="slick-slider">
                            @foreach(\App\EcommerceModel\Product::products_cat(4) as $bible)
                            <div class="product-link">
                                <div class="product-card">
                                    @if($bible->discount > 0)
                                        <div class="product-discount">₱ {{ $bible->discount }} LESS</div>
                                    @endif
                                    <a href="p{{ route('product.front.show',$bible->slug)}}">
                                        <img src="{{ asset('storage/products/'.$bible->photoPrimary) }}" alt="" />
                                        @if($bible->discount > 0)
                                        <h3 class="product-price">
                                            <div class="old" style="font-size:15px;">Php {{ number_format($bible->price,2) }}</div>
                                            Php {{ number_format($bible->price-$bible->discount,2) }}
                                        </h3>
                                        @else
                                        <h3 class="product-price"><br>Php {{ number_format($bible->price,2) }}</h3>
                                        @endif
                                    </a>
                                    <p class="product-title">{{ $bible->name }}</p>
                                    @if($bible->inventory > 0)
                                        <button type="button" onclick="add_to_cart('{{$bible->id}}');" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                            <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                        </button>
                                    @else
                                        <button type="button" class="btn out-of-stock">
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- END Our Products Bibles Tab Content -->

                    <!-- Our Products Devotional Tab Content -->
                    <div class="tab-pane fade" id="pills-devotional" role="tabpanel" aria-labelledby="pills-devotional-tab">
                        <div id="devotional" class="slick-slider">
                            @foreach(\App\EcommerceModel\Product::products_cat(6) as $devo)
                            <div class="product-link">
                                <div class="product-card">
                                    @if($devo->discount > 0)
                                        <div class="product-discount">₱ {{ $devo->discount }} LESS</div>
                                    @endif
                                    <a href="{{ route('product.front.show',$devo->slug)}}">
                                        <img src="{{ asset('storage/products/'.$devo->photoPrimary) }}" alt="" />
                                        @if($devo->discount > 0)
                                        <h3 class="product-price">
                                            <div class="old" style="font-size:15px;">Php {{ number_format($devo->price,2) }}</div>
                                            Php {{ number_format($devo->price-$devo->discount,2) }}
                                        </h3>
                                        @else
                                        <h3 class="product-price"><br>Php {{ number_format($devo->price,2) }}</h3>
                                        @endif
                                    </a>
                                    <p class="product-title">{{ $devo->name }}</p>
                                    <form>
                                        @if($devo->inventory > 0)
                                            <button type="button" onclick="add_to_cart('{{$devo->id}}');" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                                <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                            </button>
                                        @else
                                            <button type="button" class="btn out-of-stock">
                                                Out of Stock
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- END Our Products Devotional Tab Content -->

                </div>
                <!-- END Our Products Tab Contents -->
                <!-- END Home Our Products Section -->

                <div class="gap-70"></div>

                <div class="row">
                    <div class="col-lg-6 mb-xs-4">
                        <img class="bordered-img" src="{{ asset('theme/stpaul/images/misc/ads1.jpg') }}" alt="" />
                    </div>
                    <div class="col-lg-6">
                        <img class="bordered-img" src="{{ asset('theme/stpaul/images/misc/ads2.jpg') }}" alt="" />
                    </div>
                </div>

                <div class="gap-70"></div>

                <!-- Home Recommended Titles Section -->
                <div class="category-flex-2">
                    <h2 class="category-title"><span>Recommended Titles</span></h2>
                    <div class="category-nav-2">
                    <div class="product-nav">
                        <a href="" class="reco-title-prev"><span class="lnr lnr-arrow-left"></span></a>
                        <a href="" class="reco-title-next"><span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
                </div>

                <div class="gap-40"></div>

                <!-- Recommended Titles Content -->
                <div id="reco-title" class="slick-slider">
                    @php
                        $recommended_titles = \App\EcommerceModel\Product::where('status', 'PUBLISHED')->where('is_recommended',1)->orderBy('name','asc')->get(); 
                    @endphp

                    @foreach($recommended_titles->chunk(2) as $title)
                        <div class="product-link">
                            @foreach($title as $b)
                                <div class="product-card mb-4">
                                    @if($b['discount'] > 0)
                                    <div class="product-discount">₱ {{ $b['discount'] }} LESS</div>
                                    @endif
                                    <a href="{{ route('product.front.show',$b['slug'])}}">
                                        <img src="{{ asset('storage/products/'.$b->photoPrimary) }}" alt="" />
                                        @if($b['discount'] > 0)
                                        <h3 class="product-price">
                                            <div class="old" style="font-size:15px;">Php {{ number_format($b['price'],2) }}</div>
                                            Php {{ number_format($b['price']-$b['discount'],2) }}
                                        </h3>
                                        @else
                                        <h3 class="product-price"><br>Php {{ number_format($b['price'],2) }}</h3>
                                        @endif
                                    </a>
                                    <p class="product-title">{{ $b['name'] }}</p>
                                    <form id="addToCart{{$b['id']}}" data-source="">
                                        @if($b['inventory'] > 0)
                                            <button type="button" onclick="add_to_cart({{$b['id']}});" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                                <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                            </button>
                                        @else
                                            <button type="button" class="btn out-of-stock">
                                                Out of Stock
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endforeach 
                </div>
                <!-- END Recommended Titles Content -->
                <!-- END Home Recommended Titles Section -->

                <div class="gap-70"></div>

                @php
                    $onsale_items = \App\StPaulModel\OnSaleProducts::join('promos','promos.id','=','onsale_products.promo_id')->where('promos.status', 'ACTIVE')->where('promos.is_expire',0);

                    $count = $onsale_items->count();
                    $onsale_products = $onsale_items->get();
                @endphp

                @if($count > 0)
                <!-- Home Item on Sale Section -->
                <div class="category-nav-2">
                    <div class="product-nav">
                        <a href="" class="item-sale-prev"><span class="lnr lnr-arrow-left"></span></a>
                        <a href="" class="item-sale-next"><span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
                <h2 class="category-title"><span>Items on Sale</span></h2>

                <div class="gap-40"></div>

                <!-- Item on Sale Content -->
                <div id="item-sale" class="slick-slider">
                    @foreach($onsale_products as $product)
                        <div class="product-link">
                            <div class="product-card">
                                @if($product['discount'] > 0)
                                    <div class="product-discount">{{ $product->discount }}% OFF</div>
                                @endif
                                <a href="{{ route('product.front.show',$product->details->slug)}}">
                                    <img src="{{ asset('storage/products/'.$product->details->photoPrimary) }}" alt="" />
                                    <h3 class="product-price">
                                        @if($product['discount'] > 0)
                                            <div class="old" style="font-size:15px;">Php {{ number_format($product->details->price,2) }}</div>
                                        @else
                                            <br>
                                        @endif
                                        Php {{ $product->details->DiscountedPrice }}
                                    </h3>
                                </a>
                                <p class="product-title">{{ $product->details->name }}</p>
                                <form>
                                    @if($product->details->inventory > 0)
                                        <button type="button" onclick="add_to_cart('{{$product->product_id}}');" id="btn{{$product->product_id}}" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                            <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                        </button>
                                    @else
                                        <button type="button" class="btn out-of-stock">
                                            Out of Stock
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
                <!-- END Item on Sale Content -->
                <!-- END Home Item on Sale Section -->

            </div>
        </section>
        <!-- END Home Main Body Area -->

        <!-- Home Partners Area -->
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.home-partners')
        <!-- END Home Partners Logo Area -->

        <!-- Home Payments Logo Area -->
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.home-payments')
        <!-- END Home Payments Logo Area -->
    </main>
@endsection

@section('pagejs')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@endsection

@section('customjs')
    <script>
        function add_to_cart(productID) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: {
                    "product_id": productID,
                    "_token": "{{ csrf_token() }}",
                },
                type: "post",
                url: "{{route('cart.add')}}",
                // beforeSend: function(){
                //     $("#loading-overlay").show();
                // },
                success: function(returnData) {
                    //$("#loading-overlay").hide();
                    if (returnData['success']) {
                        $('.cart-counter').html(returnData['totalItems']);
                        $('.counter').html(returnData['totalItems']);
                        
                        swal({
                            toast: true,
                            position: 'center',
                            title: "Product Added to your cart!",
                            type: "success",
                            showCancelButton: true,
                            timerProgressBar: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "View Cart",
                            cancelButtonText: "Continue Shopping",
                            closeOnConfirm: false,
                            closeOnCancel: false
                            
                        },
                        function(isConfirm) {
                            if (isConfirm) {
                                window.location.href = "{{route('cart.front.show')}}";
                            } 
                            else {
                                // $('#btn'+product).html('<i class="fa fa-cart-plus bg-warning text-light p-1 rounded" title="Already added on cart"></i>');
                                swal.close();
                               
                            }
                        });
                        
                    }
                    else{
                        swal({
                            toast: true,
                            position: 'center',
                            title: "Warning!",
                            text: "We have insufficient inventory for this item.",
                            type: "warning",
                            showCancelButton: true,
                            timerProgressBar: true, 
                            closeOnCancel: false
                            
                        });
                    }
                },
                failed: function() {
                    $("#loading-overlay").hide(); 
                }
            });
        }
    </script>
@endsection