@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/jssocials/jssocials.css') }}" />
    <link rel="stylesheet" href="{{ asset('theme/stpaul/plugins/jssocials/jssocials-theme-flat.min.css') }}" />
@endsection

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <span onclick="closeNav()" class="dark-curtain"></span>
                <div class="col-lg-12 col-md-5 col-sm-12">
                    <span onclick="openNav()" class="button mb-4 d-lg-none"><span class="lnr lnr-chevron-left mr-2"></span> Options</span>
                </div>
                <div class="col-lg-3">
                    <div class="tablet-view">
                        <a href="" class="close-btn d-lg-none" onclick="closeNav()"><span class="lnr lnr-cross"></span></a>
                        <h3>News</h3>
                        <div class="gap-20"></div>
                        <div class="side-menu">
                            {!! $dates !!}
                        </div>
                        <div class="gap-40"></div>
                        <h3>Categories</h3>
                        <div class="gap-20"></div>
                        <div class="side-menu">
                            {!! $categories !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="search form-style">
                        <form id="frm_search">
                            <div class="input-group">
                                <input type="text" name="searchtxt" id="searchtxt" class="form-control form-input" placeholder="Search news"
                                    aria-label="Search news" aria-describedby="button-addon1" />
                                <span class="search-icon"><i class="fa fa-search"></i></span>
                            </div>
                            <button class="btn btn-md primary-btn" type="submit">Search</button>
                        </form>
                    </div>
                    
                    @forelse($articles as $article) 
                        <div class="news-post">
                            @if($article->thumbnail_url)
                            <div class="news-post-img" style="background-image:url('{{ $article->thumbnail_url }}')"></div>
                            @else
                            <div class="news-post-img" style="background-image:url('{{ asset('storage/news_image/news_thumbnail/No_Image_Available.jpg')}}');"></div>
                            @endif
                            
                            <div class="news-post-info">
                                <div class="news-post-content">
                                    <h3>
                                        <a href="{{ route('news.front.show',$article->slug) }}">{{ $article->name }}</a>
                                    </h3>
                                    <p class="news-post-info-excerpt">
                                        {{ $article->teaser }}
                                    </p>
                                </div>
                                <div class="news-post-share">
                                    <div class="share_link"></div>
                                    <p class="news-post-info-meta">Posted {{ Setting::date_for_news_list($article->date) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <div class="alert alert-warning" role="alert">
                                @if(isset(request()->type))
                                    <p>Your search "<i>{{ request()->criteria }}</i>" did not match any news and events.</p>
                                    <p>Suggestions:</p>
                                    <p>
                                    <ul>
                                        <li>Make sure all words are spelled correctly.</li>
                                        <li>Try different keywords.</li>
                                        <li>Try more general keywords.</li>
                                    </ul>
                                    </p>
                                @else
                                    No News article found!
                                @endif

                            </div>
                        </div>
                    @endforelse
                    {{ $articles->links('theme.'.env('FRONTEND_TEMPLATE').'.layouts.pagination') }}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials-extension.js') }}"></script>

<script>
    $(function() {
        $('#frm_search').on('submit', function(e) {
            e.preventDefault();
            window.location.href = "{{route('news.front.index')}}?type=searchbox&criteria="+$('#searchtxt').val();
        });

        $('.share_links').each(function(i, obj) {
            let link = $(this).attr('title');
            $(this).jsSocials({
                url: "{{env('APP_URL')}}"+link,
                showCount: false,
                showLabel: false,
                shares: [
                    "twitter",
                    "facebook",
                ]
            });
        });
    });
</script>
@endsection
