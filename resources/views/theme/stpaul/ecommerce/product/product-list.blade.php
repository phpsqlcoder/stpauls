@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/ion.rangeslider/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/better-rating.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/example.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/pygments.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/easyzoom.css') }}" />

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
                                <a class="listing-view-link" href="">View all categories under Books</a>

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
                                @foreach($products as $product)
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="{{ route('product.front.show',$product->slug)}}">
                                                <div class="prodImg"><img src="{{ asset('storage/products/'.$product->photoPrimary) }}" alt="" /></div>
                                                <h3 class="product-price">Php {{ number_format($product->price,2) }}</h3>
                                            </a>
                                            <p class="product-title">{{ $product->name }}</p>
                                            <form id="addToCart" data-source="addToCart">
                                                @if($product->inventory > 0)
                                                <button type="button" onclick="add_to_cart('{{$product->id}}');" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                                                    <img src="images/misc/cart.png" alt=""> Add to cart
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
                                @endforeach
                            </div>
                        </div>

                        {{ $products->links() }}

                        <ul class="pagination" style="display: none;">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
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