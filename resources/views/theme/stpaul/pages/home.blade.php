@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/owl.carousel/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/owl.carousel/owl.theme.default.min.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')
	<main>
        <!-- Home Main Body Area -->
        <section id="home-body">
            <div class="container">
                {!! $page->contents !!}
                <div class="gap-70"></div>


                <!-- Our Products Navigation Tabs -->
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
                    <div class="owl-product-nav">
                        <a href="" class="owl-product-prev"><span class="lnr lnr-arrow-left"></span></a>
                        <a href="" class="owl-product-next"><span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
                <!-- END Our Products Navigation Tabs -->
                <h2 class="category-title"><span>Our Products</span></h2>

                <div class="gap-40"></div>

                <!-- Our Products Tab Contents -->
                <div class="tab-content" id="pills-tabContent">

                    <!-- Our Products Books Tab Content -->
                    <div class="tab-pane fade show active" id="pills-book" role="tabpanel" aria-labelledby="pills-book-tab">
                        <div id="owl-product-1" class="owl-carousel owl-theme">
                            @php
                                $books = \App\EcommerceModel\Product::where('category_id',1)->where('status', 'PUBLISHED')->orderBy('name','asc')->get(); 
                            @endphp

                            @foreach($books as $book)
                            <div class="product-link">
                                <div class="product-card">
                                    <a href="{{ route('product.front.show',$book->slug)}}">
                                        <img src="{{ asset('storage/products/'.$book->photoPrimary) }}" alt="" />
                                        <h3 class="product-price">Php {{ $book->getPriceWithCurrencyAttribute() }}</h3>
                                    </a>
                                    <p class="product-title">{{ $book->name }}</p> 
                                    @if($book->inventory > 0)
                                        <button type="button" onclick="add_to_cart('{{$book->id}}');" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                            <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                        </button>
                                    @else
                                        <button type="button" class="btn add-cart-btn addToCartButton">
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
                        <div id="owl-product-2" class="owl-carousel owl-theme">
                            @php
                                $bibles = \App\EcommerceModel\Product::where('category_id',2)->where('status', 'PUBLISHED')->orderBy('name','asc')->get(); 
                            @endphp

                            @foreach($bibles as $bible)
                            <div class="product-link">
                                <div class="product-card">
                                    <a href="p{{ route('product.front.show',$bible->slug)}}">
                                        <img src="{{ asset('storage/products/'.$bible->photoPrimary) }}" alt="" />
                                        <h3 class="product-price">Php {{ $bible->getPriceWithCurrencyAttribute() }}</h3>
                                    </a>
                                    <p class="product-title">{{ $bible->name }}</p>
                                    @if($bible->inventory > 0)
                                        <button type="button" onclick="add_to_cart('{{$bible->id}}');" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                            <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                        </button>
                                    @else
                                        <button type="button" class="btn add-cart-btn addToCartButton">
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
                        <div id="owl-product-3" class="owl-carousel owl-theme">
                            @php
                                $devotionals = \App\EcommerceModel\Product::where('category_id',4)->where('status', 'PUBLISHED')->orderBy('name','asc')->get(); 
                            @endphp

                            @foreach($devotionals as $devo)
                            <div class="product-link">
                                <div class="product-card">
                                    <a href="{{ route('product.front.show',$devo->slug)}}">
                                        <img src="{{ asset('storage/products/'.$devo->photoPrimary) }}" alt="" />
                                        <h3 class="product-price">Php {{ $devo->getPriceWithCurrencyAttribute() }}</h3>
                                    </a>
                                    <p class="product-title">{{ $devo->name }}</p>
                                    <form>
                                        @if($devo->inventory > 0)
                                            <button type="button" onclick="add_to_cart('{{$devo->id}}');" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                                <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                            </button>
                                        @else
                                            <button type="button" class="btn add-cart-btn addToCartButton">
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
                    <div class="col-lg-6">
                        <img class="bordered-img" src="{{ asset('theme/stpaul/images/misc/ads1.jpg') }}" alt="" />
                    </div>
                    <div class="col-lg-6">
                        <img class="bordered-img" src="{{ asset('theme/stpaul/images/misc/ads2.jpg') }}" alt="" />
                    </div>
                </div>

                <div class="gap-70"></div>

                <!-- Home Recommended Titles Section -->
                <div class="category-nav-2">
                    <div class="owl-product-nav">
                        <a href="" class="owl-reco-title-prev"><span class="lnr lnr-arrow-left"></span></a>
                        <a href="" class="owl-reco-title-next"><span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
                <h2 class="category-title"><span>Recommended Titles</span></h2>

                <div class="gap-40"></div>

                <!-- Recommended Titles Content -->
                <div id="owl-product-4" class="owl-carousel owl-theme">
                    @php
                        $recommended_titles = \App\EcommerceModel\Product::where('status', 'PUBLISHED')->where('is_featured',1)->orderBy('name','asc')->get(); 
                    @endphp

                    @foreach($recommended_titles->chunk(2) as $title)
                        <div class="product-link">
                            @foreach($title as $b)
                                <div class="product-card">
                                    <a href="{{ route('product.front.show',$b['slug'])}}">
                                        <img src="{{ asset('storage/products/'.$b->photoPrimary) }}" alt="" />
                                        <h3 class="product-price">Php {{ number_format($b['price'],2) }}</h3>
                                    </a>
                                    <p class="product-title">{{ $b['name'] }}</p>
                                    <form id="addToCart{{$b['id']}}" data-source="">
                                        @if($b['inventory'] > 0)
                                            <button type="button" onclick="add_to_cart({{$b['id']}});" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                                <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                            </button>
                                        @else
                                            <button type="button" class="btn add-cart-btn addToCartButton">
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
                    <div class="owl-product-nav">
                        <a href="" class="owl-item-sale-prev"><span class="lnr lnr-arrow-left"></span></a>
                        <a href="" class="owl-item-sale-next"><span class="lnr lnr-arrow-right"></span></a>
                    </div>
                </div>
                <h2 class="category-title"><span>Items on Sale</span></h2>

                <div class="gap-40"></div>

                <!-- Item on Sale Content -->
                <div id="owl-product-5" class="owl-carousel owl-theme">
                    @foreach($onsale_products as $product)
                        <div class="product-link">
                            <div class="product-card">
                                <a href="{{ route('product.front.show',$product->details->slug)}}">
                                    <img src="{{ asset('storage/products/'.$product->details->photoPrimary) }}" alt="" />
                                    <h3 class="product-price">Php {{ $product->details->pricewithcurrency }}</h3>
                                </a>
                                <p class="product-title">{{ $product->details->name }}</p>
                                <form>
                                    @if($product->details->inventory > 0)
                                        <button type="button" onclick="add_to_cart('{{$product->product_id}}');" id="btn{{$product->product_id}}" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
                                            <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                        </button>
                                    @else
                                        <button type="button" class="btn add-cart-btn addToCartButton">
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
    <script src="{{ asset('theme/stpaul/plugins/owl.carousel/owl.carousel.extension.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/owl.carousel/owl.carousel.js') }}"></script>

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