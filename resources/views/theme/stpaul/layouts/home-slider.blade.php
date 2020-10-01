<div class="banner-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="padding:0;">
                <div id="banner" class="slick-slider">
                    @foreach ($page->album->banners as $banner)
                    <div class="hero-slide">
                        <img src="{{ $banner->image_path }}">
                        <div class="banner-caption">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5">
                                    </div>
                                    <div class="col-lg-7 col-md-7">
                                        <h2>{{ $banner->title }}</h2>
                                        <p>{{ $banner->description }}</p>
                                        @if($banner->url && $banner->button_text)  
                                        <a class="btn btn-lg primary-btn mt-5" href="#">{{ $banner->button_text }}</a>
                                        @endif
                                        <div class="gap-60"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
