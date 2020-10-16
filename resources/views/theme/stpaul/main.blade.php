<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
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
                    <li>
                        <div class="unit flex-row align-items-center unit-spacing-xs">
                            <div class="unit__left"><span class="icon icon-md icon-primary fab fa-facebook-f"></span></div>
                            <div class="unit__body"><a href="#">Facebook</a></div>
                        </div>
                        <div class="gap-20"></div>
                    </li>
                    <li>
                        <div class="unit flex-row align-items-center unit-spacing-xs">
                            <div class="unit__left"><span class="icon icon-md icon-primary fab fa-facebook-messenger"></span></div>
                            <div class="unit__body"><a href="#">Messenger</a></div>
                        </div>
                        <div class="gap-20"></div>
                    </li>
                    <li>
                        <div class="unit flex-row align-items-center unit-spacing-xs">
                            <div class="unit__left"><span class="icon icon-md icon-primary fab fa-twitter"></span></div>
                            <div class="unit__body"><a href="#">Twitter</a></div>
                        </div>
                        <div class="gap-20"></div>
                    </li>
                    <li>
                        <div class="unit flex-row align-items-center unit-spacing-xs">
                            <div class="unit__left"><span class="icon icon-md icon-primary fab fa-youtube"></span></div>
                            <div class="unit__body"><a href="#">Youtube</a></div>
                        </div>
                        <div class="gap-20"></div>
                    </li>
                    <li>
                        <div class="unit flex-row align-items-center unit-spacing-xs">
                            <div class="unit__left"><span class="icon icon-md icon-primary fab fa-viber"></span></div>
                            <div class="unit__body"><a href="#">Viber</a></div>
                        </div>
                        <div class="gap-20"></div>
                    </li>
                     <li>
                        <div class="unit flex-row align-items-center unit-spacing-xs">
                            <div class="unit__left"><span class="icon icon-md icon-primary fab fa-whatsapp"></span></div>
                            <div class="unit__body"><a href="#">Whatsapp</a></div>
                        </div>
                        <div class="gap-20"></div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- <div class="privacy-policy">
      <div class="privacy-policy-desc">
        <p class="title">Privacy-Policy</p>
        <p>
          This website uses cookies to ensure you get the best experience.
        </p>
      </div>
      <div class="privacy-policy-btn">
        <a class="primary-btn" href="#">Accept</a>
        <a class="default-btn" href="#">Learn More</a>
      </div>
    </div> -->

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
    </script>

    @yield('pagejs')

    @yield('customjs')
</body>

</html>