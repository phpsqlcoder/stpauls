@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
    <main>
        <section id="listing-wrapper">
            <div class="container">
                <div class="row">
                    <div id="col1" class="col-lg-3">
                        <h2 class="listing-title">Books</h2>
                        <div class="gap-10"></div>
                        <nav class="rd-navbar rd-navbar-listing">
                            <div class="listing-filter-wrap">
                                <div class="rd-navbar-listing-close-toggle rd-navbar-static--hidden toggle-original"><span class="lnr lnr-cross"></span> Close</div>
                                <h3 class="listing-category-title">Categories</h3>
                                <ul class="listing-category">
                                    <li><a href="">Apologia</a></li>
                                    <li><a href="">Asceticism</a></li>
                                    <li><a href="">Biblical Initiatives</a></li>
                                    <li><a href="">Biography</a></li>
                                    <li><a href="">Catechtics</a></li>
                                    <li><a href="">General References</a></li>
                                    <li><a href="">Hagiography</a></li>
                                    <li><a href="">Inspirationals</a></li>
                                    <li><a href="">Homiletics</a></li>
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
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="product-profile.htm">
                                                <div class="prodImg"><img src="images/misc/book1.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book2.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book1.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book2.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book1.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book2.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book1.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book2.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 item">
                                    <div class="product-link">
                                        <div class="product-card">
                                            <a href="">
                                                <div class="prodImg"><img src="images/misc/book1.jpg" alt="" /></div>
                                                <h3 class="product-price">Php 180.00</h3>
                                            </a>
                                            <p class="product-title">The Friend of the Bridegroom</p>
                                            <form id="addToCart" data-source="addToCart">
                                                <button type="button" class="btn add-cart-alt1-btn addToCartButton" data-loading-text="processing...">
                          <img src="images/misc/cart.png" alt=""> Add to cart
                        </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                </div>
            </div>
        </section>
    </main>
@endsection
