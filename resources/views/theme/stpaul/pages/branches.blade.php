@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <span onclick="closeNav()" class="dark-curtain"></span>
                <div class="col-lg-12 col-md-5 col-sm-12">
                    <span onclick="openNav()" class="button mb-4 d-lg-none"><span class="lnr lnr-chevron-left mr-2"></span> Quicklinks</span>
                </div>
                <div class="col-lg-3">
                    <div class="tablet-view">
                        <a href="" class="close-btn d-lg-none" onclick="closeNav()"><span class="lnr lnr-cross"></span></a>
                        <h2>{{$page->name}}</h2>
                        <div class="side-menu">
                            <ul>
                                <li><a href="#superstore">{{ $featured->name }}</a></li>
                                @foreach($areas as $area)
                                    @if(count($area->branches))
                                        <li><a href="#area_{{$area->id}}">{{ $area->name }} Branches</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="article-content">
                        <p>ST PAULS branches are strategically located all over the Philippines to serve you even better! Visit our Religious bookstore branches nationwide! You can also check out our <strong>Religious Books Online Philippines</strong> OR you can <strong>Buy Book Online Philippines</strong>.</p>

                        <p>&nbsp;</p>

                        <h2 id="superstore">{{ $featured->name }}</h2>
                        <div class="card">
                            <img class="m-0" src="{{ asset('storage/branches/'.$featured->id.'/'.$featured->img) }}" alt="">
                            <div class="card-body">
                                <h4>{{ $featured->name }}</h4>
                                <small>{{ $featured->branchaddress }}</small><br>
                                <small>superstore@stpauls.ph</small><br><br>
                                <small>Contacts:<br> 
                                    @foreach($featured->contacts as $contact)
                                    {{ $contact->contact_name }} - {{ $contact->contact_no }}<br>
                                    @endforeach
                                </small>
                            </div>
                        </div>

                        <p>&nbsp;</p>
                        @foreach($areas as $area)
                            @if(count($area->branches))
                                <h2 id="area_{{$area->id}}">{{ $area->name }} Branches</h2>
                                <div class="row">
                                @forelse($area->branches->where('status','ACTIVE')->where('isfeatured',0) as $branch)
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <img class="m-0" src="{{ asset('storage/branches/'.$branch->id.'/'.$branch->img) }}" alt="">
                                            <div class="card-body">
                                                <h4>{{ $branch->name }}</h4>
                                                <small>{{ $branch->branchaddress }}</small><br><br>
                                                <small>Contacts:<br> 
                                                    @foreach($branch->contacts as $contact)
                                                    {{ $contact->contact_name }} - {{ $contact->contact_no }}<br>
                                                    @endforeach
                                                </small><br>
                                                <small>{{ $branch->email }}</small><br>
                                                <small><a class="text-primary" target="_blank" href="{{ $branch->url }}">{{ $branch->url }}</a></small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                </div>
                            @endif
                            <p>&nbsp;</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
