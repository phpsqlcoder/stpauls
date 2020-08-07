@if(isset($page) && $page->album && count($page->album->banners) > 1 && $page->album->is_main_banner())
    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.home-slider')
@elseif(isset($page) && $page->album && count($page->album->banners) > 1 && !$page->album->is_main_banner())
    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.page-slider')
@elseif(isset($page) && (isset($page->album->banners) && (count($page->album->banners) == 1 && !$page->album->is_main_banner()) || !empty($page->image_url)))
    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.page-banner')
@else
    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.no-banner')
@endif