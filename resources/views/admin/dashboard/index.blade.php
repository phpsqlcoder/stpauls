@extends('admin.layouts.app')

@section('pagecss')
    <style>
        .dashboard-summary {
            height: 31rem;
        }
    </style>

@endsection
@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Welcome, {{ Auth::user()->firstname }}!</h4>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ env('APP_URL') }}" target="_blank" class="btn btn-sm pd-x-15 btn-white btn-uppercase">
                    <i data-feather="arrow-up-right" class="wd-10 mg-r-5"></i> View Website
                </a>
            </div>
        </div>

        <div class="row row-sm">
            @if (auth()->user()->has_access_to_pages_module() || auth()->user()->has_access_to_albums_module() || auth()->user()->has_access_to_user_module() || auth()->user()->has_access_to_news_module())
                <div class="col-lg-3 col-md-4">
                    @if (auth()->user()->has_access_to_pages_module() || auth()->user()->has_access_to_albums_module() || auth()->user()->has_access_to_user_module() || auth()->user()->has_access_to_news_module())
                        <div class="card dashboard-summary mg-t-20">
                            <div class="card-header">
                                Website Summary
                            </div>
                            <div class="card-body" style="height:800px !important;">
                                @if (auth()->user()->has_access_to_pages_module())
                                    <h6><strong>Pages</strong></h6>
                                    <p><a href="{{route('pages.index.advance-search')}}?status=published"><span class="badge badge-dark">{{ \App\Page::totalPublicPages() }}</span> Published Pages</a></p>
                                    <p><a href="{{route('pages.index.advance-search')}}?status=private"><span class="badge badge-dark">{{ \App\Page::totalPrivatePages() }}</span> Private Pages</a></p>
                                    <p><a href="{{route('pages.index.advance-search')}}?showDeleted=on"><span class="badge badge-dark">{{ \App\Page::totalDeletePages() }}</span> Deleted Pages</a></p>
                                    <hr>
                                @endif
                                @if (auth()->user()->has_access_to_albums_module())
                                    <h6><strong>Sub Banners</strong></h6>
                                    <p><a href="{{ route('albums.index') }}"><span class="badge badge-dark">{{ \App\Album::totalNotDeletedAlbums() }}</span>    Albums</a></p>
                                    <p><a href="{{ route('albums.index') }}?showDeleted=on"><span class="badge badge-dark">{{ \App\Album::totalDeletePages() }}</span> Deleted Albums</a></p>
                                    <hr>
                                @endif
                                @if (auth()->user()->has_access_to_user_module())
                                    <h6><strong>Users</strong></h6>
                                    <p><a href="{{ route('users.index') }}"><span class="badge badge-dark">{{ \App\User::activeTotalUser() }}</span> Active Users</a></p>
                                    <p><a href="{{ route('users.index') }}?showDeleted=on"><span class="badge badge-dark">{{ \App\User::inactiveTotalUser() }}</span> Inactive Users</a></p>
                                    <hr>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            <div class=" @if (auth()->user()->has_access_to_pages_module() || auth()->user()->has_access_to_albums_module() || auth()->user()->has_access_to_user_module() || auth()->user()->has_access_to_news_module()) col-lg-9 col-md-8 @else col-lg-12 @endif">
                <div class="card dashboard-recent mg-t-20">
                    <div class="card-header">
                        My Recent Activities
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($logs as $log)
                                <p class="list-group-item list-group-item-action">
{{--                                    <a href="{{route('settings.audit')}}?search={{$log->id}}" target="_blank">--}}
                                    <span class="badge badge-dark">{{ ucwords($log->firstname) }} {{ ucwords($log->lastname) }}</span>
{{--                                    </a> --}}
                                    {{ $log->dashboard_activity }} at {{ Setting::date_for_listing($log->activity_date) }}
                                </p>
                            @empty
                                No activities found!
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <span class="tx-12"><a href="{{ route('users.show', Auth::user()->id) }}">Show all activities <i class="fa fa-arrow-right"></i></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/nestable2/jquery.nestable.min.js') }}"></script>
@endsection

@section('customjs')
    <script></script>
@endsection
