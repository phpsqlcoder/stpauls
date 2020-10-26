@extends('theme.'.env('FRONTEND_TEMPLATE').'.main')

@section('content')
<main>
    <section id="default-wrapper">
        <div class="container">
            <div class="row">
                <div id="col1" class="col-lg-3">
                    @include('theme.'.env('FRONTEND_TEMPLATE').'.layouts.account-page-options')
                </div>
                <div id="col2" class="col-lg-9">
                    <nav class="rd-navbar">
                        <div class="rd-navbar-listing-toggle rd-navbar-static--hidden toggle-original" data-rd-navbar-toggle=".listing-filter-wrap"><span class="lnr lnr-list"></span> Options</div>
                    </nav>
                    <div class="article-content">
                        <h3 class="subpage-heading">{{ $page->name }}</h3>
                        <div>
                    <div class="tab-content" id="nav-tabContent">
                        <h4>Rider Information</h4>
                        <hr>
                        <div class="gap-10"></div>
                        <div class="form-style-alt">
                            <form autocomplete="off" method="post" class="row" action="{{ route('transaction.post-rider') }}">
                                @csrf   
                                <div class="col-lg-6">
                                    <label>Courier Name *</label>
                                    <div class="form-group">
                                        <input type="hidden" name="orderid" value="{{$sales->id}}">
                                        <input required type="text" class="form-control" name="courier_name" value="{{ old('courier_name') }}" maxlength="150">
                                    </div>
                               
                                </div>
                                <div class="col-lg-6">
                                    <label>Rider Name *</label>
                                    <div class="form-group">
                                        <input required type="text" class="form-control" name="rider_name" value="{{ old('rider_name') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label>Contact Number *</label>
                                    <div class="form-group">
                                        <input required type="text" class="form-control" name="contact_no" value="{{ old('contact_no') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label>Plate Number *</label>
                                    <div class="form-group">
                                        <input required type="text" class="form-control" name="plate_no" value="{{ old('plate_no') }}" maxlength="150">
                                    </div>
                               
                                </div>
                                <div class="col-lg-12">
                                    <label>Rider Link *</label>
                                    <div class="form-group">
                                        <input required type="text" class="form-control" name="link" value="{{ old('link') }}">
                                    </div>
                                </div>                                          
                                <div class="col-lg-6">
                                    <a href="{{ route('account-my-orders') }}" class="btn btn-md btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-md btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="gap-20"></div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection