@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/ion.rangeslider/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/better-rating.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/example.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/pygments.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/easyzoom.css') }}" />

    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/owl.carousel/owl.carousel.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/owl.carousel/owl.theme.default.min.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
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
                <div class="col-lg-3">
                    <h2 class="listing-title">{{ $product->category->name }}</h2>
                    <div class="gap-10"></div>
                    <div class="listing-filter-wrap">
                        <h3 class="listing-category-title">Categories</h3>
                        <ul class="listing-category">
                            @foreach($categories as $category)
                            <li><a href="{{ route('product.front.list',$category->slug) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                        <a class="listing-view-link" href="">View all categories under {{ $product->category->name }}</a>

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
                </div>
                <div class="col-lg-9">
                    <div class="product-wrap">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="easyzoom easyzoom--adjacent easyzoom--with-thumbnails">
                                    <a href="{{ asset('storage/products/'.$product->photoPrimary) }}">
                                        <img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="" width="100%" height="100%" />
                                    </a>
                                </div>

                                <ul class="thumbnails">
                                    @foreach($product->photos as $photo)
                                    @if($photo->is_primary == 0)
                                    <li>
                                        <a href="{{ asset('storage/products/'.$photo->path) }}" target="_blank">
                                            <img src="{{ asset('storage/products/'.$photo->path) }}" alt="" />
                                        </a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-7">
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
                                                <div class="product-rating" data-rate-value="{{ $product->ratings()->where('is_approved',1)->avg('rating') }}"></div>
                                                <span class="rating-count" style="font-size: .75em;color: #a7a7a7;margin-left: 5px;margin-top:4px;font-weight: 500;">
                                                    ({{ number_format($product->ratings()->where('is_approved',1)->avg('rating'),2) }}) Customer ratings
                                                </span>
                                            </div>
                                            
                                            <p>{{ $product->additional_info->authors }} | Product Name: {{ $product->name }}</p>
                                            @if(\App\EcommerceModel\Product::onsale_checker($product->id) > 0)
                                                <div class="product-price">
                                                    <input type="hidden" id="product_price" value="{{ $product->DiscountedPrice }}">
                                                    <span class="price-after">₱ {{ $product->DiscountedPrice }} </span>
                                                    <span class="price-before">
                                                      <div class="price-less">{{ $product->on_sale->promo_details->discount }}% Off</div>
                                                      <div class="price-original">₱ {{ number_format($product->price,2) }}</div>
                                                    </span>
                                                </div>
                                            @else
                                                <div class="product-price">
                                                    <input type="hidden" id="product_price" value="{{ $product->price }}">
                                                    <span class="price-after">₱ {{ $product->PriceWithCurrency }} </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="product-info">
                                            <p>Quantity</p>
                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                            <div class="quantity">
                                                <input type="number" name="quantity" id="qty" min="1" max="{{ $product->inventory }}" step="1" value="1" data-inc="1">
                                                <div class="quantity-nav">
                                                    <div class="quantity-button quantity-up">+</div>
                                                    <div class="quantity-button quantity-down">-</div>
                                                </div>
                                                <span class="product-pcs">pcs</span>
                                            </div>
                                            <div class="product-sku">
                                                <i class="fa fa-check high-stock"></i> {{ $product->inventory }} available stock
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
                                <a class="nav-link active" id="synopsis-tab" data-toggle="tab" href="#synopsis" role="tab" aria-controls="synopsis" aria-selected="true">Synopsis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">Details about the product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="synopsis" role="tabpanel" aria-labelledby="synopsis-tab">
                                {!! $product->additional_info->synopsis !!}
                            </div>
                            <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
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
                                        <td><p><b>Additional Information:</b> {{ $product->additional_info->additional_info }}</p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
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

                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="#" title="Back"><i class="lnr lnr-chevron-left"></i></a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">3 <span class="sr-only">(current)</span></a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">4</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#" title="Next"><i class="lnr lnr-chevron-right"></i></a>
                                            </li>
                                        </ul>
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
                                                <textarea id="message" class="form-control form-input" name="review"></textarea>
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
                    <!-- Home Item on Sale Section -->
                    <div class="category-nav-2">
                        <div class="owl-product-nav">
                            <a href="" class="owl-item-sale-prev"><span class="lnr lnr-arrow-left"></span></a>
                            <a href="" class="owl-item-sale-next"><span class="lnr lnr-arrow-right"></span></a>
                        </div>
                    </div>
                    <h2 class="category-title"><span>Related Products</span></h2>
                    <div class="gap-40"></div>

                    <div id="owl-product-5" class="owl-carousel owl-theme">
                        @foreach(explode('|',$related_products) as $rproduct)
                            @php
                                $product_info = \App\EcommerceModel\Product::find($rproduct);
                            @endphp
                            <div class="product-link">
                                <div class="product-card">
                                    <a href="{{ route('product.front.show',$product_info->slug)}}">
                                        <img src="{{ asset('storage/products/'.$product_info->photoPrimary) }}" alt="" />
                                        <h3 class="product-price">Php {{ $product_info->pricewithcurrency }}</h3>
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
    <script src="{{ asset('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/better-rating.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/easyzoom/src/easyzoom.js') }}"></script>

    <script src="{{ asset('theme/stpaul/plugins/owl.carousel/owl.carousel.extension.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/owl.carousel/owl.carousel.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="{{ asset('theme/stpaul/js/rater.js') }}"></script>
    <script>
        $(".product-rating").rate();


        $(".js-range-slider").ionRangeSlider({
            type: "double",
            grid: true,
            min:0,
            max:1000,
            from: 0,
            to: $('#product_price').val()
        });

        // Instantiate EasyZoom instances
        var $easyzoom = $('.easyzoom').easyZoom();

        // Setup thumbnails example
        var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

        $('.thumbnails').on('click', 'a', function(e) {
            var $this = $(this);

            e.preventDefault();

            // Use EasyZoom's `swap` method
            api1.swap($this.data('standard'), $this.attr('href'));
        });
        
        $(document).ready(function(){
            /** Custom Input number increment js **/
            jQuery(".quantity").each(function() {
                var spinner = jQuery(this),
                    input = spinner.find('input[type="number"]'),
                    btnUp = spinner.find(".quantity-up"),
                    btnDown = spinner.find(".quantity-down"),
                    min = input.attr("min"),
                    max = input.attr("max"),
                    valOfAmout = input.val(),
                    newVal = 0;

                btnUp.on("click", function() {
                    var varholder = input.val();
                    var oldValue = parseFloat(input.val());

                    if (varholder === "") {
                        var newVal = 1;
                    } else {
                        if (oldValue >= max) {
                            var newVal = oldValue;
                        } else {
                            var newVal = oldValue + 1;
                        }
                    }
                    spinner.find("input").val(newVal);
                    spinner.find("input").trigger("change");
                });

                btnDown.on("click", function() {
                    var varholder = input.val();
                    var oldValue = parseFloat(input.val());

                    if (varholder === "") {
                        var newVal = 1;
                    } else {
                        if (oldValue <= min) {
                            var newVal = oldValue;
                        } else {
                            var newVal = oldValue - 1;
                        }
                    }
                    spinner.find("input").val(newVal);
                    spinner.find("input").trigger("change");
                });
            });
        });
    </script>
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
                    "qty": $('#qty').val(),
                    "price": $('#product_price').val(),
                    "_token": "{{ csrf_token() }}",
                },
                type: "post",
                url: "{{route('cart.add')}}",
                success: function(returnData) {
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