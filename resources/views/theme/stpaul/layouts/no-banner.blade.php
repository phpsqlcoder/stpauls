<div class="banner-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="padding:0;">
                <div class="sub-banner-caption">
                    <div class="container">
                        <h2>{{ $page->name }}</h2>
                        @if(isset($breadcrumb))
                            <ol class="breadcrumb">
                                @foreach($breadcrumb as $link => $url)
                                    @if($loop->last)
                                        <li class="breadcrumb-item active" aria-current="page">{{$link}}</li>
                                    @else
                                        <li class="breadcrumb-item"><a href="{{$url}}">{{$link}}</a></li>
                                    @endif
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>
                <div id="banner" class="slick-slider">
                    <div class="hero-slide">
                        <img src="{{ asset('theme/stpaul/images/banners/sub/subbanner.jpg') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>