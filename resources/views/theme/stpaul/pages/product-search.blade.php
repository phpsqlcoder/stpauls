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
                        <div class="listing-filter-wrap">
                            <div class="rd-navbar-listing-close-toggle rd-navbar-static--hidden toggle-original"><span class="lnr lnr-cross"></span> Close</div>
                            <h3 class="listing-category-title">Categories</h3>
                            <ul class="listing-category">
                                @foreach($categories as $category)
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
                                    <span class="rating-count">561</span>
                                </a>
                            </div>
                            <div class="rating">
                                <a id="four-star" href="">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="rating-count">459</span>
                                </a>
                            </div>
                            <div class="rating">
                                <a id="three-star" href="">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="rating-count">200</span>
                                </a>
                            </div>
                            <div class="rating">
                                <a id="two-star" href="">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="rating-count">2</span>
                                </a>
                            </div>
                            <div class="rating">
                                <a id="one-star" href="">
                                    <span class="fa fa-star checked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="fa fa-star unchecked"></span>
                                    <span class="rating-count">12</span>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-lg-9">
                    <!-- START SEARCH RESULT -->
                    <p class="font-weight-bold mb-3">{{$count}} results found for the keyword '<i>{{$keyword}}</i>'</p>
                    <!-- START SEARCH RESULT -->
                    <div class="filter-product">
                        <div class="row">
                            <div id="col2" class="col-6">
                                <nav class="rd-navbar">
                                    <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Filter</div>
                                </nav>
                                <div class="btn-group">
                                    <button type="button" class="btn dropdown-filter-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Items displayed
                </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">18</a>
                                        <a class="dropdown-item" href="#">36</a>
                                        <a class="dropdown-item" href="#">72</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="btn-group">
                                    <p class="filter-item-count ml-auto">6817 Item/s found</p>
                                    <button type="button" class="btn dropdown-filter-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sort by
                </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">18</a>
                                        <a class="dropdown-item" href="#">36</a>
                                        <a class="dropdown-item" href="#">72</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gap-20"></div>
                    <div class="list-product">
                        <div class="row">
                        	@forelse($products as $product)
                            <div class="col-md-4 col-sm-6 item">
                                <div class="product-link">
                                    <div class="product-card">
                                        <a href="product-profile.htm">
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
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton">
                                                    <img src="images/misc/cart.png" alt=""> Out of Stock
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty

                            @endforelse
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
    <script src="{{ asset('theme/stpaul/plugins/aos/dist/aos.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/ion.rangeslider/js/ion.rangeSlider.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/better-rating.js') }}"></script>

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
                    "qty": $('#qty').val(),
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
