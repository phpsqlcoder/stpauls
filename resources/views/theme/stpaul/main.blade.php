<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>ST PAULS Online | Catholic Online Bookstore in Philippines</title>
    <link rel="shortcut icon" href="{{ asset('theme/stpaul/images/misc/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/bootstrap/css/bootstrap.css') }}" />
    
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/linearicon/linearicon.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/responsive-tabs/css/responsive-tabs.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/tagsinput.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/rd-navbar/rd-navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/aos/dist/aos.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/css/style.css') }}" />

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
            @include('theme.sysu.layout.footer')
        </footer>
        <!-- END Page Footer -->
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

    <div id="sticky-contact" class="sticky-hover">
        <img src="{{ asset('theme/stpaul/images/misc/stpauls_contactus.png') }}" />
    </div>

    <script type="text/javascript">
        var bannerFxIn = "fadeIn";
        var bannerFxOut = "fadeOut";
        var bannerCaptionFxIn = "fadeInUp";
        var autoPlayTimeout = 4000;
        var bannerID = "banner";
    </script>

    <script src="{{ asset('theme/stpaul/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('theme/stpaul/js/script.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/materialize/js/materialize.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/rd-navbar/dist/js/jquery.rd-navbar.js') }}"></script>
    @yield('pagejs')

    @yield('customjs')
</body>

</html>