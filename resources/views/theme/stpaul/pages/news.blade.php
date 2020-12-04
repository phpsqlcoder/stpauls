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
                        <div class="article-opt">
                            <p>
                                <a href="{{ route('news.front.index') }}"><span><i class="fa fa-long-arrow-alt-left"></i></span>Back to news listing</a>
                            </p>
                            <p>
                                <a data-toggle="modal" data-target="#email-article"><span><i class="fa fa-share"></i></span>E-mail this article</a>
                            </p>
                            <p>
                                <a href="{{ route('news.front.print', $news->slug) }}"><span><i class="fa fa-print"></i></span>Print this article</a>
                            </p>
                        </div>
                        <div class="gap-20"></div>
                        <div class="article-widget">
                            <div class="article-widget-title">
                                Latest News
                            </div>
                            @foreach($latestArticles as $latest)
                            <div class="article-widget-news">
                                <p class="news-date">{{ date('F d, Y',strtotime($latest->date)) }}</p>
                                <p class="news-title">
                                    <a href="{{ route('news.front.show',$latest->slug) }}">{{ $latest->teaser }}</a>
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('home') }}"> Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('news.front.index')}}">News</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">{{$news->name}}</a></li>
                    </ol>
                    <div class="gap-20"></div>
                    <h2>{{$news->name}}</h2>
                    <div class="article-meta-share">
                        <div class="article-meta">
                            <p>
                                Posted {{ Setting::date_for_news_list($news->updated_at) }}, by
                                <span class="article-meta-author">{{$news->user->name}}</span>
                            </p>
                        </div>
                        <div class="article-share">
                            <div class="article-share-text">Share:</div>
                            <div id="article-social"></div>
                        </div>
                    </div>
                    <div class="article-content">
                        @if (!empty($news->image_url))
                            <img src="{{ $news->image_url }}" alt="News image" />
                        @endif
                        {!! $news->contents !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="modal fade" id="email-article" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">E-mail this article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="shareEmailForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>E-mail to *</label>
                        <input type="email" name="email_to" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Recipient's Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Your E-mail Address *</label>
                        <input type="email" name="email_from" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Your Name *</label>
                        <input type="text" name="sender_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnSendArticle"><span id="spanSendArticle">Send Article</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="email-success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">E-mail this article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Article successfully sent!
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="email-failed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">E-mail this article</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
               Failed to share email. Try it again later.
           </div>
       </div>
   </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials.js') }}"></script>
    <script src="{{ asset('theme/stpaul/plugins/jssocials/jssocials-extension.js') }}"></script>

    <script>
        $(function() {
            $('#shareEmailForm').submit(function(evt) {
                evt.preventDefault();
                let data = $('#shareEmailForm').serialize();

                $('#spanSendArticle').html('Sending...');
                $('#btnSendArticle').prop('disabled',true);
                
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('news.front.share', $news->slug) }}",
                    success: function(returnData) {
                        $('#email-success').modal('show');
                        $('#email-article').modal('hide');
                        $('#email-article input').val('');
                    },
                    error: function(){
                        $('#email-failed').modal('show');
                        $('#email-article').modal('hide');
                        $('#email-article input').val('');
                    }
                });
                return false;
            });
        });
    </script>
@endsection
