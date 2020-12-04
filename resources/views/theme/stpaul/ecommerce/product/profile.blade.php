@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/ion.rangeslider/css/ion.rangeSlider.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/better-rating.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/slick/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/slick/slick-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/xZoom/src/xzoom.css') }}" />

    {{-- <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/example.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/pygments.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/easyzoom.css') }}" /> --}}

    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/sweetalert2/sweetalert.min.css') }}" />
    <style>
        .product-rating .fa-star.checked {
            color: #ffb800;
        }
    </style>
@endsection

@section('content')
<main>
    <section id="product-wrapper">
    <!-- <section id="home-body"> -->
        <div class="container">
            <div class="row">
                <div id="col1" class="col-lg-3">
                    <h2 class="listing-title">{{ $product->category->name }}</h2>
                    <div class="gap-10"></div>
                    <nav class="rd-navbar rd-navbar-listing">
                    <div class="listing-filter-wrap">
                        <div class="rd-navbar-listing-close-toggle rd-navbar-static--hidden toggle-original"><span class="lnr lnr-cross"></span> Close</div>
                        <h3 class="listing-category-title">Categories</h3>
                        <ul class="listing-category">
                            @foreach($categories as $category)
                            <li><a href="{{ route('product.front.list',$category->slug) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                        
                        @if(count($product->category->child_categories))
                        <a href="#" class="listing-view-link" data-toggle="collapse" data-target="#child_categories" class="accordion-toggle" >View all categories under {{ $product->category->name }}</a>
                        @endif

                        <ul class="listing-category collapse" id="child_categories">
                            @foreach($product->category->child_categories as $category)
                            <li><a href="{{ route('product.front.list',$category->slug) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>

                        <div class="gap-70"></div>

                        <h3 class="listing-filter-title">Price Range</h3>
                        <div class="gap-10"></div>
                        <input type="text" class="js-range-slider" name="my_range" value="" />

                        <div class="gap-70"></div>

                        <h3 class="listing-filter-title">Ratings</h3>
                        <div class="gap-10"></div>
                        <div class="rating">
                            <a id="five-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="rating-count">{{\App\EcommerceModel\ProductReview::review_counter($product->id,5)}}</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="four-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">{{\App\EcommerceModel\ProductReview::review_counter($product->id,4)}}</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="three-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">{{\App\EcommerceModel\ProductReview::review_counter($product->id,3)}}</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="two-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">{{\App\EcommerceModel\ProductReview::review_counter($product->id,2)}}</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="one-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">{{\App\EcommerceModel\ProductReview::review_counter($product->id,1)}}</span>
                            </a>
                        </div>
                    </div>
                    </nav>
                </div>
                <div id="col2" class="col-lg-9">
                    <nav class="rd-navbar">
                        <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Filter</div>
                    </nav>
                    <div class="product-wrap">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="xzoom-container">
                                    <img class="xzoom" id="xzoom-default" src="{{ asset('storage/products/'.$product->photoPrimary) }}"
                                        xoriginal="{{ asset('storage/products/'.$product->photoPrimary) }}" />
                                </div>
                                <div id="product-gallery-slider" class="slick-slider">
                                    @foreach($product->photos as $photo)
                                    <a href="{{ asset('storage/products/'.$photo->path) }}" class="xzoom-link">
                                        <img class="xzoom-gallery" src="{{ asset('storage/products/'.$photo->path) }}" alt="{{$product->id}}">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <input type="hidden" id="productID" value="{{ $product->id }}">
                                <form id="addToCart" data-source="addToCart" method="post" action="{{ route('product-buy-now') }}">
                                    @csrf
                                    <div class="product-detail">
                                        <div class="product-description">
                                            <ol class="breadcrumb">
                                                @php 
                                                    $arr = \App\EcommerceModel\ProductCategory::product_category($product->category_id);
                                                @endphp

                                                @foreach($arr as $key => $a)
                                                    <li class="breadcrumb-item active" aria-current="page">{{ $a->name }}</li>
                                                @endforeach
                                            </ol>
                                            <h2>{{ $product->name }}</h2>
                                            <hr>
                                            <div class="form-row">
                                                <div class="product-rating" data-rate-value="{{ $product->ratings()->where('is_approved',1)->where('rating','>',0)->avg('rating') }}"></div>
                                                <span class="rating-count" style="font-size: .75em;color: #a7a7a7;margin-left: 5px;margin-top:4px;font-weight: 500;">
                                                    ({{ number_format($product->ratings()->where('is_approved',1)->where('rating','>',0)->avg('rating'),2) }}) Customer ratings
                                                </span>
                                            </div>
                                            
                                            <p>{{ $product->additional_info->authors }} | Product Code: {{ $product->code }}</p>
                                            @if(\App\EcommerceModel\Product::onsale_checker($product->id) > 0)
                                                <div class="product-price">
                                                    <input type="hidden" id="product_price" value="{{ $product->discountedprice }}">
                                                    <span class="price-after">₱ {{ $product->discountedprice }} </span>
                                                    <span class="price-before">
                                                      <div class="price-less">{{ $product->on_sale->promo_details->discount }}% Off</div>
                                                      <div class="price-original">₱ {{ number_format($product->price,2) }}</div>
                                                    </span>
                                                </div>
                                            @else
                                                @if($product->discount > 0)
                                                <div class="product-price">
                                                    <input type="hidden" id="product_price" value="{{ $product->price-$product->discount }}">
                                                    <span class="price-after">₱ {{ number_format($product->price-$product->discount,2) }}</span>
                                                    <span class="price-before">
                                                      <div class="price-less">₱ {{$product->discount }} LESS</div>
                                                      <div class="price-original">₱ {{ number_format($product->price,2) }}</div>
                                                    </span>
                                                </div>
                                                @else
                                                <div class="product-price">
                                                    <input type="hidden" id="product_price" value="{{ $product->price }}">
                                                    <span class="price-after">₱ {{ $product->PriceWithCurrency }} </span>
                                                </div>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="product-info">
                                            <p>Quantity</p>
                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                            <div class="quantity">
                                                <input type="number" name="quantity" id="qty" min="1" max="{{ $product->Maxpurchase }}" step="1" value="1" data-inc="1">
                                                <div class="quantity-nav">
                                                    <div class="quantity-button quantity-up">+</div>
                                                    <div class="quantity-button quantity-down">-</div>
                                                </div>
                                                <span class="product-pcs">{{ $product->uom }}</span>
                                            </div>
                                            <div class="product-sku">
                                                <input type="hidden" id="input_avail_stock" value="{{ $product->inventory }}">
                                                <i class="fa fa-check high-stock"></i> <span id="available_stock">{{ $product->inventory }}</span> available stock
                                            </div>
                                        </div>
                                        <div class="product-btn">
                                            @if($product->inventory > 0)
                                            <button type="button" onclick="add_to_cart('{{$product->id}}');" class="btn btn-lg add-cart-alt2-btn addToCartButton">
                                                <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/cart.png" alt=""> Add to cart
                                            </button>

                                            <button type="submit" class="btn btn-lg buy-now-btn buyNowButton">
                                                <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/blitz.png" alt=""> Buy Now
                                            </button>
                                            @endif

                                            @if(Auth::check())
                                            <div class="product-wishlist">
                                                <input name="wishlist" id="wishlist" data-product-id="333" type="checkbox" @if(\App\EcommerceModel\Wishlist::product_wishlist($product->id) > 0) checked @endif/>
                                                <label for="wishlist">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 255.7 225.13">
                                                        <path style="color:#000000;enable-background:accumulate;"
                                                        d="M128,69.9s-17-48.25-63-48.25S7.71,75.32,7.71,75.32s-11.36,39.74,39.74,89.29L128,233.77l80.55-69.16c51.09-49.55,39.74-89.29,39.74-89.29S236.9,21.65,191,21.65,128,69.9,128,69.9Z"
                                                        transform="translate(-0.13 -15.15)" fill="transparent" id="heart-path" stroke="#F8332A" stroke-width="15" marker="none" visibility="visible"
                                                            display="inline" overflow="visible" />
                                                    </svg>
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="gap-30"></div>

                    <div class="product-additional">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if($tab == 'details') active @endif" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">Details about the product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="synopsis-tab" data-toggle="tab" href="#synopsis" role="tab" aria-controls="synopsis" aria-selected="true">{{ ($product->category->name == 'Books') ? 'Synopsis' : 'Prayer' }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if($tab == 'reviews') active @endif" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade @if($tab == 'details') show active @endif" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                                <table>
                                    <tr>
                                        <td><p><b>Weight (grams):</b> {{ $product->weight }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Author/s:</b> {{ $product->additional_info->authors }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Size:</b> {{ $product->size }} {{ $product->uom }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Materials:</b> {{ $product->additional_info->materials }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>No of Pages:</b> {{ $product->additional_info->no_of_pages }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>ISBN:</b> {{ $product->additional_info->isbn }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Editorial Reviews:</b> {{ $product->additional_info->editorial_reviews }}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>About the Author:</b> {!! $product->additional_info->about_author !!}</p></td>
                                    </tr>
                                    <tr>
                                        <td><p><b>Description:</b> {!! $product->description !!}</p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="synopsis" role="tabpanel" aria-labelledby="synopsis-tab">
                                {!! $product->additional_info->synopsis !!}
                            </div>
                            <div class="tab-pane fade @if($tab == 'reviews') show active @endif" id="review" role="tabpanel" aria-labelledby="review-tab">
                                @if($reviews_count > 0)
                                    <!-- START REVIEW WRAP -->
                                    <div class="review-wrap">
                                        @foreach($reviews as $review)
                                            <div class="review-body">
                                                <div class="vcard">
                                                    <h3>{{ $review->customer->fullname }}</h3>
                                                    <span>{{ date('F d, Y',strtotime($review->created_at)) }}</span>
                                                </div>
                                              
                                                <div class="rating small">
                                                    @for($x = 1; $x <= $review->rating; $x++)
                                                    <span class="fa fa-star checked"></span>
                                                    @endfor
                                                </div>
                                              
                                                <div class="gap-10"></div>
                                              
                                                <p>
                                                    {{ $review->review }}
                                                </p>
                                            </div>
                                        @endforeach
                                        
                                        <div class="gap-20"></div>
                                        {!! $reviews->links() !!}
                                    </div>
                                    <!-- END REVIEW WRAP -->
                                @else
                                    <div class="empty-review-wrap">
                                        <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/comment.png" />
                                        <p>There are no reviews yet.<br />
                                            @if(\App\EcommerceModel\SalesDetail::rate_product($product->id) > 0)
                                                Be the first to review “{{ $product->name }}”
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <div class="gap-40"></div>
                                @if(\App\EcommerceModel\SalesDetail::rate_product($product->id) > 0)
                                    <form method="post" action="{{ route('product.review.store') }}">
                                        @csrf
                                        <div class="form-style-alt fs-sm">
                                            <h2>We want to know your opinion!</h2>
                                            <label for="rating-count"><b>Your Rating</b></label>
                                            <div class="rating">
                                                <i class="fa fa-star" data-rate="1"></i>
                                                <i class="fa fa-star" data-rate="2"></i>
                                                <i class="fa fa-star" data-rate="3"></i>
                                                <i class="fa fa-star" data-rate="4"></i>
                                                <i class="fa fa-star" data-rate="5"></i>
                                                <input type="hidden" id="rating-count" name="rating" value="0">
                                            </div>
                                            <div class="gap-20"></div>
                                            <div class="form-wrap">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <textarea id="message" class="form-control form-input" name="review" maxlength="150"></textarea>
                                                <label class="form-label textarea" for="message">Tell us what you thought about it</label>
                                            </div>
                                        </div>
                                        <div class="gap-20"></div>
                                        <button type="submit" class="btn btn-md primary-btn">Submit</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="gap-80"></div>
                    @php
                        $related_products = \App\EcommerceModel\ProductTag::related_products($product->id);
                    @endphp

                    @if($related_products)
                    <div id="default-wrapper" class="p-0">
                                
                        <div class="category-flex-2">
                            <h2 class="category-title"><span>Related Products</span></h2>
                            <div class="category-nav-2">
                                <div class="product-nav">
                                    <a href="" class="related-product-prev"><span class="lnr lnr-arrow-left"></span></a>
                                    <a href="" class="related-product-next"><span class="lnr lnr-arrow-right"></span></a>
                                </div>
                            </div>
                        </div>

                        <div class="gap-40"></div>

                        <!-- Recently Viewed Content -->
                        <div id="related-products" class="slick-slider">
                        @foreach(explode('|',$related_products) as $rproduct)
                            @php
                                $product_info = \App\EcommerceModel\Product::find($rproduct);
                            @endphp
                            <div class="product-link">
                                <div class="product-card">
                                    <a href="{{ route('product.front.show',$product_info->slug)}}">
                                        <img src="{{ asset('storage/products/'.$product_info->photoPrimary) }}" alt="" />
                                        <h3 class="product-price"><div class="old"><br></div>Php {{ $product_info->pricewithcurrency }}</h3>
                                    </a>
                                    <p class="product-title">{{ $product_info->name }}</p>
                                    <form>
                                        @if($product_info->inventory > 0)
                                            <button type="button" onclick="add_to_cart('{{$product_info->id}}');" id="btn{{$product_info->id}}" class="btn add-cart-btn addToCartButton" data-loading-text="processing...">
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
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/aos/dist/aos.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/ion.rangeslider/js/ion.rangeSlider.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/better-rating.js') }}"></script>

    <script src="{{ asset('theme/stpaul/plugins/sweetalert2/sweetalert.min.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/rater.js') }}"></script>
    <script>
        $(".product-rating").rate();


        $(".js-range-slider").ionRangeSlider({
            type: "double",
            grid: true,
            min:0,
            max:5000,
            from: 0,
            to: $('#product_price').val()
        });
    </script>
@endsection


@section('customjs')
    <script src="{{ asset('theme/stpaul/plugins/xZoom/src/xzoom.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/xZoom/src/hammer.js/jquery.hammer.min.js') }}"></script>

    <script>
        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                
                var prodID = $('#productID').val();
                $.ajax({
                    data: {
                        "product_id": prodID,
                        "_token": "{{ csrf_token() }}",
                    },
                    type: "post",
                    url: "{{route('product.add-to-wishlist')}}",
                    success: function(returnData) {
                        swal({
                            title: '',
                            text: "Product has been added to wishlist.",         
                        });
                    }
                });
            }
            else if($(this).prop("checked") == false){
                var prodID = $('#productID').val();
                $.ajax({
                    data: {
                        "product_id": prodID,
                        "_token": "{{ csrf_token() }}",
                    },
                    type: "post",
                    url: "{{route('product.remove-to-wishlist')}}",
                    success: function(returnData) {
                        swal({
                            title: '',
                            text: "Product has been removed to wishlist.",         
                        });
                    }
                });
            }
        });

        function add_to_cart(productID) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var available_stock = $('#input_avail_stock').val();
            var ordered_qty     = $('#qty').val(); 
            var inventory = parseFloat(available_stock)-parseFloat(ordered_qty);

            $.ajax({
                data: {
                    "product_id": productID,
                    "qty": $('#qty').val(),
                    "price": $('#product_price').val(),
                    "_token": "{{ csrf_token() }}",
                },
                type: "post",
                url: "{{route('cart.add')}}",
                success: function(returnData) {
                    $('#input_avail_stock').val(inventory);
                    $('#available_stock').html(inventory);
                    if (returnData['success']) {
                        $('.cart-counter').html(returnData['totalItems']);
                        $('.counter').html(returnData['totalItems']);
                        
                        swal({
                            toast: true,
                            position: 'center',
                            title: "Product added to your cart!",
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