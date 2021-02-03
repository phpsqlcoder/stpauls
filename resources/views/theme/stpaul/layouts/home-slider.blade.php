<div class="banner-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" style="padding:0;">
                <div id="banner" class="slick-slider">
                    @foreach ($page->album->banners as $banner)
                    <div class="hero-slide">
                        <img src="{{ $banner->image_path }}">
                        @if($banner->url && $banner->button_text)  
                        <div class="banner-button">
                            <div class="container position-relative d-flex justify-content-center">
                                <a class="btn btn-md primary-btn" href="{{ $banner->url }}" target="_blank">{{ $banner->button_text }}</a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
