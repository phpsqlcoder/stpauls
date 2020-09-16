<header class="page-head">
    <nav class="rd-navbar">
        <!-- RD Navbar Upper Panel -->
        <div class="rd-navbar-upper-panel">
            <div class="rd-navbar-upper-panel__toggle rd-navbar-fixed__element-1 rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".rd-navbar-nav-wrap-left"><span></span></div>
            <div class="rd-navbar-upper-panel__content">
                <div class="rd-navbar-upper-panel__left">
                    <div class="rd-navbar-nav-wrap-left">
                        <!-- RD Navbar Nav - TOP -->
                        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.menu')
                        <!-- END RD Navbar Nav - TOP -->
                    </div>
                </div>
                <div class="rd-navbar-upper-panel__right">
                    <ul class="list-inline-xxs">
                        <li>
                            <p class="country-list">Philippine-Macau</li>
                        <li><img src="{{ asset('theme/stpaul/images/misc/philippine-flag.jpg') }}" alt=""></li>
                        <li><img src="{{ asset('theme/stpaul/images/misc/macau-flag.jpg') }}" alt=""></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- END RD Navbar Upper Panel -->

        <div class="rd-navbar-inner">
            <!-- RD Navbar Panel -->
            <div class="rd-navbar-panel rd-navbar-n-search-wrap">
                <!-- RD Navbar Toggle -->
                <div class="rd-navbar-fixed__element-4">
                    <button class="rd-navbar-n-search__toggle rd-navbar-n-search__toggle_additional" data-rd-navbar-toggle=".rd-navbar-n-search-wrap"></button>
                </div>
                <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                <!-- END RD Navbar Toggle -->
                <div class="rd-navbar-panel__content">
                    <div class="rd-navbar-panel__left">
                        <!-- RD Navbar Brand -->
                        <div class="rd-navbar-brand">
                            <a href="{{ route('home') }}" class="brand-name">
                                <img src="{{ asset('theme/stpaul/images/misc/logo.png') }}" alt="StPaul" width="240" />
                            </a>
                        </div>
                        <!-- END RD Navbar Brand -->
                    </div>
                    <div class="rd-navbar-panel__center">
                        <div class="rd-navbar-n-search rd-navbar-search_not-collapsable">
                            <form class="rd-n-search" action="{{ route('product.front.search') }}" method="POST" data-search-live="rd-search-results-live">
                                @csrf
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="search" value="on">
                                <div class="form-wrap">
                                    <input required class="form-input" id="rd-navbar-n-search-form-input" type="text" name="searchtxt" autocomplete="off">
                                    <label class="form-label rd-input-label" for="rd-navbar-n-search-form-input">Search a product</label>
                                    <div class="rd-n-search-results-live" id="rd-n-search-results-live"></div>
                                </div>
                                <button class="rd-n-search__submit" type="submit"></button>
                            </form>
                        </div>
                    </div>
                    <div class="rd-navbar-panel__right">
                        <a href="{{ route('cart.front.show')}}">
                            <ul class="list-inline-primary">
                                <li>
                                    <div class="unit flex-row align-items-center unit-spacing-xs">
                                        <div class="unit__left">
                                            <img class="icon icon-xxs icon-primary" src="{{ asset('theme/stpaul/images/misc/cart.png') }}" alt="">
                                        </div>
                                        <div class="unit__body">
                                            <ul class="list-semicolon cart-header-title">
                                                <li>Cart</li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <p class="cart-header-counter">(<span class="cart-counter">{!! Setting::EcommerceCartTotalItems() !!}</span>) Items</p>
                                </li>
                            </ul>
                        </a>
                    </div>
                </div>
            </div>

            <div class="rd-navbar-nav-wrap">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-7">
                            <!-- RD Navbar Nav -->
                            @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.product-category-menu')
                            <!-- END RD Navbar Nav -->
                        </div>
                        <div class="col-lg-5">
                            <ul class="list-inline-secondary">
                                @if(Auth::check())
                                    <li><a href="{{ route('my-account.change-password') }}">Change Password</a></li>
                                    <li>
                                        <a class="acc-menu active-user dropdown-toggle" href="" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">{{ \App\EcommerceModel\Customer::customer_username(auth()->user()->id) }}</a>
                                        <div class="acc-dropdown-menu dropdown-menu dropdown-menu-right " aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="{{ route('my-account.manage-account') }}"><span class="lnr lnr-user mr-2"></span>Manage Account</a>
                                            <a class="dropdown-item" href="{{route('account-transactions') }}"><span class="lnr lnr-user mr-2"></span>Manage Orders</a>
                                            <a class="dropdown-item" href="{{ route('customer.logout') }}"><span class="lnr lnr-exit mr-2"></span>Log Out</a>
                                        </div>
                                    </li>
                                @else
                                    <li><a href="{{ route('customer-front.sign-up') }}">Register</a></li>
                                    <li><a href="{{ route('ecommerce.forgot_password') }}">Forgot Password</a></li>
                                    <li><a href="{{ route('ecommerce.reactivate-account') }}">Re-activate Account</a></li>
                                    <li>
                                        <a class="acct-login-btn" href="{{ route('customer-front.login') }}">
                                            <i class="icon icon-xxxs icon-white lnr lnr-user"></i>
                                            <span class="acct-login-divider"></span>LOGIN
                                        </a>
                                    </li>
                                @endif   
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END RD Navbar Panel -->

            <div class="rd-navbar-bottom-nav">
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <a class="item" href="index.htm">
                                <span class="lnr lnr-home"></span>
                                <span class="text">Home</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a class="item" href="cart.htm">
                                <span class="lnr lnr-cart">
                <span class="counter">3</span>
                                </span>
                                <span class="text">Cart</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a class="item" href="login.htm">
                                <span class="lnr lnr-user"></span>
                                <span class="text">Account</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- END RD Navbar -->
</header>