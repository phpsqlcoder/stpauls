<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>ST PAULS Online | Catholic Online Bookstore in Philippines</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{  asset('storage/icons/'.Setting::getFaviconLogo()->website_favicon) }}">

    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/bootstrap/css/bootstrap.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/font-awesome/css/all.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/linearicon/css/linearicons.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/slick/slick.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/slick/slick-theme.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/css/tagsinput.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/rd-navbar/rd-navbar.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/plugins/aos/dist/aos.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('theme/stpaul/css/style.css') }}" />

    

    @yield('pagecss')

    {!! \Setting::info()->google_analytics !!}
    {{ \App\StPaulModel\Promo::update_promo_xpiration() }}

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
</head>

<body id="app">
    <div class="page">

        <!-- Page Head -->
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.header')
        <!-- END Page Head -->
        @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.banner')
        <!-- Page Content -->
        @yield('content')
        <!-- END Page Content -->

        <!-- Page Footer -->
        <footer>
            @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.footer')
        </footer>
        <!-- END Page Footer -->

        <!-- SHORTCUT LINKS RIGHT SIDE CONTACT US -->
        <nav class="rd-navbar rd-navbar-sl">
            <div class="sl-filter-wrap">
                <div class="rd-navbar-sl-close-toggle toggle-original"><span class="lnr lnr-cross"></span> Close</div>
                <div class="gap-20"></div>
                <ul class="rd-navbar-items-list">
                    @php 
                        $media_accounts = \App\MediaAccounts::all();
                    @endphp

                    @foreach($media_accounts as $media)
                        <li>
                            <div class="unit flex-row align-items-center unit-spacing-xs">
                                <div class="unit__left"><span class="icon icon-md icon-primary fab {{ \App\MediaAccounts::icons($media->name) }}"></span></div>
                                <div class="unit__body"><a href="{{$media->media_account}}" target="_blank">{{ ucwords($media->account_name) }}</a></div>
                            </div>
                            <div class="gap-20"></div>
                        </li>
                    @endforeach
                        <li>&nbsp;</li>
                        <li>&nbsp;</li>
                        <li>
                            <div class="unit flex-row align-items-center unit-spacing-xs">
                                <div class="unit__left"><i style="font-size: 22px;width: 30px;text-align: center;" class="fab fa-viber"></i></div>
                                <div class="unit__body"><a href="#">{{ \Setting::info()->viber_no }}</a></div>
                            </div>
                            <div class="gap-20"></div>
                        </li>
                        <li>
                            <div class="unit flex-row align-items-center unit-spacing-xs">
                                <div class="unit__left"><i style="font-size: 22px;width: 30px;text-align: center;" class="fas fa-mobile-alt"></i></div>
                                <div class="unit__body"><a href="#">{{ \Setting::info()->mobile_no }}</a></div>
                            </div>
                            <div class="gap-20"></div>
                        </li>
                        <li>
                            <div class="unit flex-row align-items-center unit-spacing-xs">
                                <div class="unit__left"><i style="font-size: 22px;width: 30px;text-align: center;" class="fas fa-phone-square-alt"></i></div>
                                <div class="unit__body"><a href="#">{{ \Setting::info()->tel_no }}</a></div>
                            </div>
                            <div class="gap-20"></div>
                        </li>
                        <li>
                            <div class="unit flex-row align-items-center unit-spacing-xs">
                                <div class="unit__left"><i style="font-size: 22px;width: 30px;text-align: center;" class="fas fa-fax"></i></div>
                                <div class="unit__body"><a href="#">{{ \Setting::info()->fax_no }}</a></div>
                            </div>
                            <div class="gap-20"></div>
                        </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- PRIVACY POLICY WIDGET -->
    <div class="privacy-policy dark" style="display: none;" id="popupPrivacy">
      <div class="privacy-policy-desc">
        <p class="title">Privacy-Policy</p>
        <p>
          {!! \Setting::info()->data_privacy_popup_content !!}
        </p>
      </div>
      <div class="privacy-policy-btn">
        <button type="button" class="btn btn-lg primary-btn" id="popup-close">Accept</button>
        <a class="btn btn-lg default-btn" href="{{route('privacy-policy')}}">Learn More</a>
      </div>
    </div>

    <div id="top">
        <img src="{{ asset('theme/stpaul/images/misc/top.png') }}" />
    </div>

    <!-- SHORTCUT LINKS RIGHT SIDE CONTACT US -->
    <div id="sticky-contact" class="sticky-hover">
        <nav class="rd-navbar">
            <div class="rd-navbar-sl-toggle toggle-original" data-rd-navbar-toggle=".sl-filter-wrap">
                <img src="{{ asset('theme/stpaul/images/misc/stpauls_contactus.png') }}" />
            </div>
        </nav>
    </div>

    <script type="text/javascript">
        var bannerFxIn = "fadeIn";
        var bannerFxOut = "fadeOut";
        var bannerCaptionFxIn = "fadeInUp";
        var autoPlayTimeout = 4000;
        var bannerID = "banner";
    </script>

    <script src="{{ asset('theme/stpaul/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/materialize/js/materialize.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/rd-navbar/dist/js/jquery.rd-navbar.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/slick/slick.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/slick/slick.extension.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/aos/dist/aos.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/flexmenu/modernizr.custom.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/flexmenu/flexmenu.min.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/script.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if(localStorage.getItem('popState') != 'shown'){
                $('#popupPrivacy').delay(1000).fadeIn();
            }
        });

        $('#popup-close').click(function() // You are clicking the close button
        {
            $('#popupPrivacy').fadeOut(); // Now the pop up is hidden.
            localStorage.setItem('popState','shown');
        });

        $(function () {
            $('header nav .rd-navbar-nav-wrap .rd-navbar-nav').flexMenu({
                linkTitle: "",
                linkText: "More"
            });
            if ($(window).width() <= 991) {
                $('header nav .rd-navbar-nav-wrap .rd-navbar-nav').flexMenu({
                    undo: "true"
                });
            }
        });
        $(window).on("resize", function () {
            if ($(window).width() <= 991) {
                $('header nav .rd-navbar-nav-wrap .rd-navbar-nav').flexMenu({
                    undo: "true"
                });
            } else {
                $('header nav .rd-navbar-nav-wrap .rd-navbar-nav').flexMenu({
                    linkTitle: "",
                    linkText: "More"
                });
            }
        });

        $('#productSearchForm').submit(function(e){
            e.preventDefault();

            $('#searching').show();
            $.ajax({
                type: "GET",
                url: "{{ route('product.front.search') }}",
                data: $('#productSearchForm').serialize(),
                success: function( response ) {
                    $('#searching').hide();
                    $('#productSearchResult').html(response);
                    $('#productSearchResult').show();
                }
            });
        });
    </script>

    @yield('pagejs')

    @yield('customjs')
</body>

</html>