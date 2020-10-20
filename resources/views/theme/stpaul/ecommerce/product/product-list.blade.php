@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/ion.rangeslider/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/better-rating.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endsection

@section('content')
    <main>
        <section id="listing-wrapper">
            <div class="container">
                <div class="row">
                    <div id="col1" class="col-lg-3">
                        <h2 class="listing-title">{{ $page->name }}</h2>
                        <div class="gap-10"></div>
                        <nav class="rd-navbar rd-navbar-listing">
                            <form action="{{ route('product.front.list',$category->slug) }}" id="filter_form" method="POST" class="row">
                            @csrf
                                <input type="hidden" name="sort" id="sort" value="@if(request()->has('sort')) {{$request->sort}}  @endif">
                                <input type="hidden" name="limit" id="limit" value="@if(request()->has('limit')) {{$request->limit}} @else 16 @endif">
                                <input type="hidden" name="search" value="on">  

                                <div class="listing-filter-wrap">
                                    <div class="rd-navbar-listing-close-toggle rd-navbar-static--hidden toggle-original"><span class="lnr lnr-cross"></span> Close</div>
                                    <h3 class="listing-category-title">Categories</h3>
                                    <ul class="listing-category">
                                        @foreach($categories as $category)
                                            <li><a href="{{ route('product.front.list',$category->slug) }}">{{ $category->name }}</a></li>
                                        @endforeach
                                    </ul>

                                    @if(count($page->child_categories))
                                    <a href="#" class="listing-view-link" data-toggle="collapse" data-target="#child_categories" class="accordion-toggle" >View all categories under {{ $page->name }}</a>
                                    @endif

                                    <ul class="listing-category collapse" id="child_categories">
                                        @foreach($page->child_categories as $category)
                                        <li><a href="{{ route('product.front.list',$category->slug) }}">{{ $category->name }}</a></li>
                                        @endforeach
                                    </ul>

                                    <div class="gap-70"></div>

                                    <h3 class="listing-filter-title">Price Range</h3>
                                    <div class="gap-10"></div>
                                    <input type="hidden" name="rating" id="rating" value="@if(request()->has('rating')) {{$request->rating}}  @endif">
                                    <input type="hidden" id="product_max_price" value="{{$productMaxPrice}}">
                                    <input type="hidden" id="min_price_range" value="{{ $minPrice }}">
                                    <input type="hidden" id="max_price_range" value="{{ $maxPrice }}">
                                    <input type="text" class="js-range-slider" name="price" id="price" value="" />

                                    <div class="gap-70"></div>

                                    <h3 class="listing-filter-title">Ratings</h3>
                                    <div class="gap-10"></div>
                                    <div class="rating">
                                        <a href="" onclick="ratings(5);">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="rating-count">{{ \App\EcommerceModel\ProductReview::category_rating_counter($page->id,5) }}</span>
                                        </a>
                                    </div>
                                    <div class="rating">
                                        <a href="" onclick="ratings(4);">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="rating-count">{{ \App\EcommerceModel\ProductReview::category_rating_counter($page->id,4) }}</span>
                                        </a>
                                    </div>
                                    <div class="rating">
                                        <a href="" onclick="ratings(3);">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="rating-count">{{ \App\EcommerceModel\ProductReview::category_rating_counter($page->id,3) }}</span>
                                        </a>
                                    </div>
                                    <div class="rating">
                                        <a href="" onclick="ratings(2);">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="rating-count">{{ \App\EcommerceModel\ProductReview::category_rating_counter($page->id,2) }}</span>
                                        </a>
                                    </div>
                                    <div class="rating">
                                        <a href="" onclick="ratings(1);">
                                            <span class="fa fa-star checked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="fa fa-star unchecked"></span>
                                            <span class="rating-count">{{ \App\EcommerceModel\ProductReview::category_rating_counter($page->id,1) }}</span>
                                        </a>
                                    </div>
                                    <div class="gap-30"></div>
                                    <a href="#" class="btn btn-primary btn-sm text-light" onclick="$('#filter_form').submit();">Apply Filter</a>
                                    <a href="#" class="btn btn-success btn-sm text-light" onclick="reset_form();">Clear All</a>
                                </div>
                            </form>
                        </nav>
                    </div>
                    <div class="col-lg-9">
                        <div class="filter-product">
                            <div class="row">
                                <div id="col2" class="col-6">
                                    <nav class="rd-navbar">
                                        <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Filter</div>
                                    </nav>
                                    <div class="btn-group">
                                        <button type="button" class="btn dropdown-filter-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @if(request()->has('limit'))
                                                {{$request->limit}}
                                            @else
                                                40
                                            @endif items
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#" onclick="filter_limit('16')">16</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('24')">24</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('40')">40</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('60')">60</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('100')">100</a>
                                            <a class="dropdown-item" href="#" onclick="filter_limit('All')">All</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="btn-group">
                                        <p class="filter-item-count ml-auto">{{$total_product}} Item/s found</p>
                                        <button type="button" class="btn dropdown-filter-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Sort by
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" onclick="filter_sort('Price low to high')">Price low to high</a>
                                            <a class="dropdown-item" href="#" onclick="filter_sort('Price high to low')">Price high to low</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="gap-20"></div>
                        <div class="list-product">
                            <div class="row">
                                @foreach($products as $product)
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 pl-xs-1 pr-xs-1 item">
                                    <div class="product-link p-0">
                                        <div class="product-card">
                                            <a href="{{ route('product.front.show',$product->slug)}}">
                                                <div class="prodImg"><img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="" /></div>
                                                <h3 class="product-price">Php {{ number_format($product->price,2) }}</h3>
                                            </a>
                                            <p class="product-title">{{ $product->name }}</p>
                                            <form id="addToCart" data-source="addToCart">
                                                @if($product->inventory > 0)
                                                <button type="button" onclick="add_to_cart('{{$product->id}}');" class="btn add-cart-alt1-btn addToCartButton">
                                                    <img src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt=""> Add to cart
                                                </button>
                                                @else
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton">Out of Stock</button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{ $products->appends($_POST)->links() }}
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/ion.rangeslider/js/ion.rangeSlider.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    
    <script>
        $(".js-range-slider").ionRangeSlider({
            type: "double",
            grid: true,
            min:1,
            max:$('#product_max_price').val(),
            from: $('#min_price_range').val(),
            to: $('#max_price_range').val()
        });
    </script>
@endsection

@section('customjs')
    <script>
        function ratings(rating){
            $('#rating').val(rating);
            $('#filter_form').submit(); 
        }

        function reset_form(){
            var maxPrice = $('#product_max_price').val();
            $('#sort').val('');
            $('#limit').val(40);
            $('#price').val('1;'+maxPrice);
            $('#rating').val('');
            $('#filter_form').submit(); 
        }

        function filter_sort(par){
            $('#sort').val(par);
            $('#filter_form').submit(); 
        }

        function filter_limit(par){
            $('#limit').val(par);
            $('#filter_form').submit();     
        }   

        function add_to_cart(productID) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                data: {
                    "product_id": productID,
                    "qty": $('#qty').val(),
                    "_token": "{{ csrf_token() }}",
                },
                type: "post",
                url: "{{route('cart.add')}}",
                success: function(returnData) {
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