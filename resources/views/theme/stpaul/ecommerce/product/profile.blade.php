@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/ion.rangeslider/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/better-rating.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/example.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/pygments.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/easyzoom/css/easyzoom.css') }}" />
@endsection

@section('content')
<main>
    <section id="product-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <h2 class="listing-title">{{ $product->category->name }}</h2>
                    <div class="gap-10"></div>
                    <div class="listing-filter-wrap">
                        <h3 class="listing-category-title">Categories</h3>
                        <ul class="listing-category">
                            @foreach($categories as $category)
                            <li><a href="">{{ $category->name }}</a></li>
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
                                <span class="rating-count">561</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="four-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">459</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="three-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">200</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="two-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">2</span>
                            </a>
                        </div>
                        <div class="rating">
                            <a id="one-star" href="">
                                <span class="fa fa-star checked"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="fa fa-star"></span>
                                <span class="rating-count">12</span>
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
                                    <li>
                                        <a href="images/misc/book1.jpg" data-standard="images/misc/book1.jpg">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                        </a>
                                    </li>
                                    <li>
                                        <a href="images/misc/book1.jpg" data-standard="images/misc/book1.jpg">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                        </a>
                                    </li>
                                    <li>
                                        <a href="images/misc/book1.jpg" data-standard="images/misc/book1.jpg">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                        </a>
                                    </li>
                                    <li>
                                        <a href="images/misc/book1.jpg" data-standard="images/misc/book1.jpg">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                        </a>
                                    </li>
                                    <li>
                                        <a href="images/misc/book1.jpg" data-standard="images/misc/book1.jpg">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-7">
                                <form id="addToCart" data-source="addToCart">
                                    <div class="product-detail">

                                        <div class="product-description">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item active" aria-current="page">Books</li>
                                                <li class="breadcrumb-item active" aria-current="page">General References</li>
                                            </ol>
                                            <h2>{{ $product->name }}</h2>
                                            <hr>
                                            <div class="rating">
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="fa fa-star checked"></span>
                                                <span class="rating-count">(23) Customer ratings</span>
                                            </div>
                                            <p>Patrick James | Product Name: Clemson Men's Lifestyle Shoes<br> This is really worth it.</p>
                                            <div class="product-price">
                                                <span class="price-after">₱ 160.00</span>
                                                <span class="price-before">
                      <div class="price-less">70% Off</div>
                      <div class="price-original">₱ 160.00</div>
                    </span>
                                            </div>
                                        </div>

                                        <div class="product-info">
                                            <p>Quantity</p>
                                            <div class="quantity">
                                                <input type="number" name="quantity" min="1" max="25" step="1" value="1" data-inc="1">
                                                <span class="product-pcs">pcs</span>
                                            </div>
                                            <div class="product-sku">
                                                <i class="fa fa-check high-stock"></i> 25 available stock
                                            </div>
                                        </div>

                                        <div class="product-btn">
                                            <button type="button" class="btn btn-lg add-cart-alt2-btn addToCartButton" data-loading-text="processing...">
                      <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/cart.png" alt=""> Add to cart
                    </button>
                                            <form id="buyNow" data-source="buyNow">
                                                <button type="button" class="btn btn-lg buy-now-btn buyNowButton" data-loading-text="processing...">
                      <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/blitz.png" alt=""> Buy Now
                    </button>

                                                <div class="product-wishlist">
                                                    <input name="wishlist" id="wishlist" data-product-id="333" type="checkbox" />
                                                    <label for="wishlist">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 255.7 225.13">
                        <path style="color:#000000;enable-background:accumulate;"
                          d="M128,69.9s-17-48.25-63-48.25S7.71,75.32,7.71,75.32s-11.36,39.74,39.74,89.29L128,233.77l80.55-69.16c51.09-49.55,39.74-89.29,39.74-89.29S236.9,21.65,191,21.65,128,69.9,128,69.9Z"
                          transform="translate(-0.13 -15.15)" fill="transparent" id="heart-path" stroke="#F8332A" stroke-width="15" marker="none" visibility="visible"
                            display="inline" overflow="visible" />
                      </svg>
                    </label>
                                                </div>
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
                                        <td><p><b>Additioanl Information:</b> {{ $product->additional_info->additional_info }}</p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                <div class="empty-review-wrap">
                                    <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/comment.png" />
                                    <p>There are no reviews yet.<br />Be the first to review “The Friend of the Bridegroom”</p>
                                </div>
                                <div class="gap-40"></div>
                                <form id='leave-review'>
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
                                            <textarea id="message" class="form-control form-input" name="message"></textarea>
                                            <label class="form-label textarea" for="message">Tell us what you thought about it</label>
                                        </div>
                                    </div>
                                    <div class="gap-20"></div>
                                    <button type="submit" class="btn btn-md primary-btn">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="gap-80"></div>
                    <div class="product-related">
                        <h2 class="listing-title">Related Products</h2>
                        <div class="gap-10"></div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 item">
                                <div class="product-link">
                                    <div class="product-card">
                                        <a href="product-profile.htm">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                            <h3 class="product-price">Php 180.00</h3>
                                        </a>
                                        <p class="product-title">The Friend of the Bridegroom</p>
                                        <form id="addToCart1" data-source="addToCart">
                                            <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                      <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/cart.png" alt=""> Add to cart
                    </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 item">
                                <div class="product-link">
                                    <div class="product-card">
                                        <a href="">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                            <h3 class="product-price">Php 180.00</h3>
                                        </a>
                                        <p class="product-title">The Friend of the Bridegroom</p>
                                        <form id="addToCart2" data-source="addToCart">
                                            <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                      <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/cart.png" alt=""> Add to cart
                    </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 item">
                                <div class="product-link">
                                    <div class="product-card">
                                        <a href="">
                                            <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/book1.jpg" alt="" />
                                            <h3 class="product-price">Php 180.00</h3>
                                        </a>
                                        <p class="product-title">The Friend of the Bridegroom</p>
                                        <form id="addToCart3" data-source="addToCart">
                                            <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                      <img src="{{\URL::to('/')}}/theme/stpaul/images/misc/cart.png" alt=""> Add to cart
                    </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.subscribe-form')
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/aos/dist/aos.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/ion.rangeslider/js/ion.rangeSlider.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/vanilla-zoom/vanilla-zoom.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/better-rating.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/easyzoom/src/easyzoom.js') }}"></script>

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
        
    </script>
@endsection
